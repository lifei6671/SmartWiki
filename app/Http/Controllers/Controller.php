<?php

namespace SmartWiki\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use Response;
use Illuminate\Http\Request;
use SmartWiki\Models\Member;

/**
 * Class Controller
 * @package SmartWiki\Http\Controllers
 * @property Response $response
 */
class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
    /**
     * @var array 输出的数据
     */
    protected $data = array();
    /**
     * @var \Illuminate\Http\Request 请求对象
     */
    protected $request;
    /**
     * @var Response 响应
     */
    protected $response;
    /**
     * @var Member
     */
    protected $member;
    /**
     * 当前登录用户id
     * @var int
     */
    protected $member_id = null;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->response = response();
        $member = session_member();
        if(empty($member) === false){
            $this->member = $member;
            /**
             * @var Member
             */
            $this->data['member'] = $member;
            $this->member_id =$member->member_id;
        }
    }

    /**
     * 判断是否是POST请求
     * @return bool
     */
    protected function isPost()
    {
        return strcasecmp($this->request->getMethod(),'post') === 0;
    }

    /**
     * 判断是否是GET请求
     * @return bool
     */
    protected function isGet()
    {
        return strcasecmp($this->request->getMethod(),'get') === 0;
    }

    /**
     * 输出 Json 信息
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResult($errcode, $data = null,$message = null)
    {

        $message = empty($message) ? config('errors.'.$errcode) : $message;

        $content = ['errcode' => $errcode, 'message' => $message];
        if(empty($data) === false){
            $content['data'] = $data;
        }

        $this->response = $this->response->json($content)
            ->header('Pragma','no-cache')
            ->header('Cache-Control','no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        return $this->response;
    }
}
