<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/1/16 0016
 * Time: 11:02
 */

namespace SmartWiki\Http\Controllers;


use SmartWiki\Models\Project;

class SearchController extends Controller
{
    public function search()
    {
        //如果没有启用匿名访问
        if(!wiki_config('ENABLE_ANONYMOUS',false) && empty($this->member)){
            return redirect(route('account.login'));
        }

        $keyword = $this->request->get('keyword');
        $pageIndex = intval($this->request->input('page',1));

        $this->data['lists'] =  Project::search($keyword,$pageIndex,20,$this->member_id);
        $this->data['keyword'] = $keyword;


        //var_dump($this->data);exit;
        return view('search.search',$this->data);
    }
}