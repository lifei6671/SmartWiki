<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/10/29
 * Time: 12:34
 */

namespace SmartWiki\Http\Controllers;

use League\Flysystem\Exception;
use SmartWiki\Models\Document;
use SmartWiki\Models\DocumentHistory;
use SmartWiki\Models\Project;
use Illuminate\Auth\Access\AuthorizationException;


class DocumentController extends Controller
{
    /**
     * 文档编辑首页
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id)
    {
        if(empty($id) or $id <= 0){
            abort(404);
        }
        $project = Project::find($id);
        if(empty($project)){
            abort(404);
        }
        //判断是否有编辑权限
        if(Project::hasProjectEdit($id,$this->member->member_id) === false){
            abort(403);
        }

        $jsonArray = Project::getProjectArrayTree($id);

        if(empty($jsonArray) === false){
            $jsonArray[0]['state']['selected'] = true;
            $jsonArray[0]['state']['opened'] = true;
        }
        $this->data['project_id'] = $id;
        $this->data['project'] = $project;
        $this->data['json'] = json_encode($jsonArray,JSON_UNESCAPED_UNICODE);


        return view('document.document',$this->data);
    }

    /**
     * 显示文档历史
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history($id)
    {
        $doc_id = intval($id);

        if($doc_id <= 0){
            abort(404);
        }
        $document = Document::find($doc_id);
        if(empty($document)){
            abort(404);
        }
        if(Project::hasProjectEdit($document->project_id,$this->member_id) == false){
            abort(403);

        }
        $page = max(intval($this->request->input('page',1)),1);

        $this->data['document'] = $document;

        $this->data['lists'] = DocumentHistory::getDocumentHistoryByDocumentId($doc_id,$page);

        return view('document.history',$this->data);
    }

    /**
     * 删除历史文档记录
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteHistory()
    {
        $history_id = intval($this->request->input('id'));
        if($history_id <= 0){
            return $this->jsonResult(50502);
        }

        $history = DocumentHistory::find($history_id);
        if(empty($history)){
            return $this->jsonResult(40901);
        }

        $document = Document::find($history->doc_id);

        if(empty($document)){
            return $this->jsonResult(40301);
        }
        if(Project::hasProjectEdit($document->project_id,$this->member_id) == false){
            return $this->jsonResult(40305);
        }

        if($history->delete()){
            return $this->jsonResult(0);
        }else {
            return $this->jsonResult(500);
        }
    }

    /**
     * 恢复到指定版本
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreHistory()
    {
        $history_id = intval($this->request->input('id'));
        if($history_id <= 0){
            return $this->jsonResult(50502);
        }

        $history = DocumentHistory::find($history_id);
        if(empty($history)){
            return $this->jsonResult(40901);
        }

        $document = Document::find($history->doc_id);

        if(empty($document)){
            return $this->jsonResult(40301);
        }
        if(Project::hasProjectEdit($document->project_id,$this->member_id) == false){
            return $this->jsonResult(40305);
        }

        $document->doc_name = $history->doc_name;
        $document->doc_content = $history->doc_content;
        $document->parent_id = $history->parent_id;
        if($document->save()){
            return $this->jsonResult(0,["doc_id" => $document->doc_id]);
        }else {
            return $this->jsonResult(500);
        }
    }
    /**
     * 获取文档编辑内容
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContent($id)
    {
        if (empty($id) or $id <= 0) {
            abort(404);
        }

        $doc = Document::find($id);
        if (empty($doc)) {
            return $this->jsonResult(40301);
        }
        $role = Project::hasProjectShow($doc->project_id, $this->member_id);
        if ($role == false) {
            return $this->jsonResult(40305);
        }
        $this->data['doc']['doc_id'] = $doc->doc_id;
        $this->data['doc']['name'] = $doc->doc_name;
        $this->data['doc']['project_id'] = $doc->project_id;
        $this->data['doc']['parent_id'] = $doc->parent_id;
        $this->data['doc']['content'] = $doc->doc_content;

        unset($this->data['member']);
        return $this->jsonResult(0, $this->data);
    }

    /**
     * 保存文档
     * @return \Illuminate\Http\JsonResponse
     */
    public function save()
    {
        $project_id = $this->request->input('project_id');

        if($this->isPost()){
            $document = null;

            $content = $this->request->input('editormd-markdown-doc',null);
            //如果是保存文档内容
            if(empty($content) === false){
                $doc_id = intval($this->request->input('doc_id'));
                if($doc_id <= 0){
                    return $this->jsonResult(40301);
                }
                $document = Document::find($doc_id);
                if(empty($document)){
                    return $this->jsonResult(40301);
                }
                //如果没有编辑权限
                if(Project::hasProjectEdit($document->project_id,$this->member_id) == false){
                    return $this->jsonResult(40305);
                }
                //如果文档内容没有变更
                if(strcasecmp(md5($content),md5($document->doc_content)) === 0) {
                    return $this->jsonResult(0, ['doc_id' => $doc_id, 'parent_id' => $document->parent_id, 'name' => $document->doc_name]);
                }
                $document->doc_content = $content;
                $document->modify_at = $this->member_id;
            }else {
                //如果是新建文档
                if (Project::hasProjectEdit($project_id, $this->member_id) == false) {
                    return $this->jsonResult(40305);
                }
                $doc_id = intval($this->request->input('id', 0));
                $parentId = intval($this->request->input('parentId', 0));
                $name = trim($this->request->input('documentName', ''));
                $sort = intval($this->request->input('sort'));

                //文档名称不能为空
                if (empty($name)) {
                    return $this->jsonResult(40303);
                }


                //查看是否存在指定的文档
                if ($doc_id > 0) {
                    $document = Document::where('project_id', '=', $project_id)->where('doc_id', '=', $doc_id)->first();
                    if (empty($document)) {
                        return $this->jsonResult(40301);
                    }
                }
                //判断父文档是否存在
                if ($parentId > 0) {
                    $parentDocument = Document::where('project_id', '=', $project_id)->where('doc_id', '=', $parentId)->first();
                    if (empty($parentDocument)) {
                        return $this->jsonResult(40301);
                    }
                }

                if($doc_id > 0) {
                    //查看是否有重名文档
                    $doc = Document::where('project_id', '=', $project_id)->where('doc_name', '=', $name)->where('doc_id','<>',$doc_id)->first(['doc_id']);
                    if (empty($doc) === false) {
                        return $this->jsonResult(40304);
                    }
                }else{
                    //查看是否有重名文档
                    $doc = Document::where('project_id', '=', $project_id)->where('doc_name', '=', $name)->first(['doc_id']);
                    if (empty($doc) === false) {
                        return $this->jsonResult(40304);
                    }
                }

                if (empty($document) === false and $document->parent_id == $parentId and strcasecmp($document->doc_name, $name) === 0 and $sort <= 0) {
                    return $this->jsonResult(0, ['doc_id' => $doc_id, 'parent_id' => $parentId, 'name' => $name]);
                }

                $document = $document ?: new Document();

                $document->project_id = $project_id;
                $document->doc_name = $name;
                $document->parent_id = $parentId;

                if ($doc_id <= 0) {
                    $document->create_at = $this->member_id;
                    $sort = Document::where('parent_id','=',$parentId)->orderBy('doc_sort','DESC')->first(['doc_sort']);

                    $sort = ($sort ? $sort['doc_sort'] : -1) + 1;

                }else{
                    $document->modify_at = $this->member_id;
                }

                if($sort > 0) {
                    $document->doc_sort = $sort;
                }
            }

            if($document->save() === false){
                return $this->jsonResult(500,null,'保存失败');
            }
            $data = ['doc_id' => $document->doc_id.'','parent_id' => ($document->parent_id == 0 ? '#' : $document->parent_id .''),'name' => $document->doc_name];

            return $this->jsonResult(0,$data);
        }

        return $this->jsonResult(405);
    }

    /**
     * 删除文档
     * @param int $doc_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($doc_id)
    {
        $doc_id = intval($doc_id);

        if($doc_id <= 0){
            return $this->jsonResult(40301);
        }

        $doc = Document::find($doc_id);
        //如果文档不存在
        if(empty($doc)){
            return $this->jsonResult(40301);
        }
        //判断是否有编辑权限
        if(Project::hasProjectEdit($doc->project_id,$this->member_id) == false){
            return $this->jsonResult(40305);
        }
        $result = Document::deleteDocument($doc_id);

        if($result){
            return $this->jsonResult(0);
        }else{
            return $this->jsonResult(500);
        }
    }

    /**
     * 文件上传
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload()
    {
        $allowExt = ["jpg", "jpeg", "gif", "png"];
        //如果上传的是图片
        if(isset($_FILES['editormd-image-file'])){
            //如果没有开启图片上传
            if(!env('UPLOAD_IMAGE_ENABLE','0')){
                $data['success'] = 0;
                $data['message'] = '没有开启图片上传功能';
                return $this->response->json($data);
            }
            $file = $this->request->file('editormd-image-file');
            $allowExt = explode('|',env('UPLOAD_IMAGE_EXT','jpg|jpeg|gif|png'));
        }elseif(isset($_FILES['editormd-file-file'])){
            //如果没有开启文件上传
            if(!env('UPLOAD_FILE_ENABLE','0')){
                $data['success'] = 0;
                $data['message'] = '没有开启文件上传功能';
                return $this->response->json($data);
            }

            $file = $this->request->file('editormd-file-file');
            $allowExt = explode('|',env('UPLOAD_FILE_EXT','txt|doc|docx|xls|xlsx|ppt|pptx|pdf|7z|rar'));
        }
        $dirPath = public_path('uploads/' . date('Ym'));
        //如果目标目录不能创建
        if (!is_dir($dirPath) && !mkdir($dirPath)) {
            $data['success'] = 0;
            $data['message'] = '上传目录没有创建文件夹权限';
            return $this->response->json($data);
        }
        //如果目标目录没有写入权限
        if(is_dir($dirPath) && !is_writable($dirPath)) {
            $data['success'] = 0;
            $data['message'] = '上传目录没有写入权限';
            return $this->response->json($data);
        }

        //校验文件
        if(isset($file) && $file->isValid()){
            $ext = $file -> getClientOriginalExtension(); //上传文件的后缀
            //判断是否是图片
            if(empty($ext) or in_array(strtolower($ext),$allowExt) === false){
                $data['success'] = 0;
                $data['message'] = '不允许的文件类型';

                return $this->response->json($data);
            }
            //生成文件名
            $fileName = uniqid() . '_' . dechex(microtime(true)) .'.'.$ext;
            try{
                $path = $file->move('uploads/' . date('Ym'),$fileName);

                $webPath = '/' . $path->getPath() . '/' . $fileName;

                $data['success'] = 1;
                $data['message'] = 'ok';
                $data['alt'] = $file->getClientOriginalName();
                $data['url'] = url($webPath);
                if(isset($_FILES['editormd-file-file'])){
                    $data['icon'] = resolve_attachicons($ext);
                }

                return $this->response->json($data);

            }catch (Exception $ex){
                $data['success'] = 0;
                $data['message'] = $ex->getMessage();

                return $this->response->json($data);
            }

        }
        $data['success'] = 0;
        $data['message'] = '文件校验失败';

        return $this->response->json($data);
    }

    /**
     * 显示文档
     * @param int $doc_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws AuthorizationException
     */
    public function show($doc_id)
    {
        $doc_id = intval($doc_id);
        if($doc_id <= 0){
            abort(404);
        }

        $doc = Document::getDocumentFromCache($doc_id);

        if(empty($doc) ){
            abort(404);
        }
        $project = Project::getProjectFromCache($doc->project_id);

        if(empty($project)){
            abort(404);
        }

        $permissions = Project::hasProjectShow($project->project_id,$this->member_id);

        //校验是否有权限访问文档
        if($permissions === 0){
            abort(404);
        }elseif($permissions === 2){
            $role = session_project_role($project->project_id);
            if(empty($role)){
                $this->data = $project;
                return view('home.password',$this->data);
            }
        }else if($permissions === 3 && empty($member_id)){
            return redirect(route("account.login"));
        }elseif($permissions === 3) {
            abort(403);
        }

        $this->data['project'] = Project::getProjectFromCache($doc->project_id);

        $this->data['tree'] = Project::getProjectHtmlTree($doc->project_id,$doc->doc_id);
        $this->data['title'] = $doc->doc_name;

        if(empty($doc->doc_content) === false){
            $this->data['body'] = Document::getDocumnetHtmlFromCache($doc_id);
        }else{
            $this->data['body'] = '';
        }

        if($this->request->ajax()){
            unset($this->data['member']);
            unset($this->data['project']);
            unset($this->data['tree']);
            $this->data['doc_title'] = $doc->doc_name;


            return $this->jsonResult(0,$this->data);
        }

        return view('home.kancloud',$this->data);
    }

    /**
     * 保存排序信息
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function sort($id)
    {
        if(Project::hasProjectEdit($id,$this->member_id) == false){
            return $this->jsonResult(40305);
        }

        $params = $this->request->getContent();
        if(empty($params) === false){
            $params = json_decode($params,true);

            if(empty($params) === false){
                foreach ($params as $item){
                    $data = ['parent_id'=>$item['parent'],'doc_sort'=>$item['sort'],'modify_at' => $this->member_id];

                    Document::where('project_id','=',$id)->where('doc_id','=',$item['id'])->update($data);
                }
            }
        }
        return $this->jsonResult(0);
    }

    public function export($id)
    {
        if($id <= 0){
            abort(404);
        }
        $project = Project::getProjectFromCache($id);

        if(empty($project)){
            abort(404);
        }

        $member_id = null;
        if(empty($this->member) === false){
            $member_id = $this->member->member_id;

        }

        $permissions = Project::hasProjectShow($id,$member_id);

        //校验是否有权限访问文档
        if($permissions === 0){
            abort(404);
        }elseif($permissions === 2){
            $role = session_project_role($id);
            if(empty($role)){
                $this->data = $project;
                return view('home.password',$this->data);
            }
        }else if($permissions === 3 && empty($member_id)){
            return redirect(route("account.login"));
        }elseif($permissions === 3) {
            abort(403);
        }

        $tree = Project::getProjectArrayTree($id);


        $filename = $project->project_name;

        header('pragma:public');
        header('Content-type:application/vnd.ms-word;charset=utf-8;name="'.$filename.'".doc');
        header("Content-Disposition:attachment;filename=$filename.doc");
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        $path = public_path('static/styles/kancloud.css');
        $content = '';

        if(file_exists($path)){
            $content .= '<head><style type="text/css">' . file_get_contents($path) . '</style></head>';
        }
        $content .= '<body><div class="m-manual"><div class="manual-article"><div class="article-content"><div class="article-body editor-content"><div style="width: 100%;text-align: center;font-size: 25px;padding: 50px 0;"><h1>' . $project->project_name . '</h1></div>';



        $content .= self::recursion('#',1,$tree) . '</div></div></div></div></body>';

        echo output_word($content);
    }

    private static function recursion($parent,$level,&$tree)
    {
        global $content;
        $content .= '';

        if ($level > 7) {
            $level = 6;
        }
        foreach ($tree as $item) {

            if ($item['parent'] == $parent) {

                $doc = Document::getDocumnetHtmlFromCache($item['id']);

                if ($doc !== false) {
                    $text = '<h' . $level . '>' . $item['text'] . '</h' . $level . '>'  . '<div style="margin: 50px auto;">' . $doc . '</div>';

                    $content  .= $text;
                }

                $key = array_search($item['id'], array_column($tree, 'parent'));

                if ($key !== false) {
                    $level++;
                    self::recursion($item['id'], $level, $tree);
                }
            }
        }
        $content .= '';
        return $content;
    }
}