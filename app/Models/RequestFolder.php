<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/2/14 0014
 * Time: 13:15
 */

namespace SmartWiki\Models;

use DB;
/**
 * 接口分类表
 * @property int $classify_id
 * @property int $member_id
 * @property int $classify_name
 * @property string $description
 * @property int $classify_sort
 * @property int $parent_id
 * @property int $api_count
 * Class ApiClassify
 * @package SmartWiki\Models
 */
class RequestFolder extends ModelBase
{
    protected $table = 'request_folder';
    protected $primaryKey = 'classify_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['classify_id'];

    public $timestamps = false;

    /**
     * 获取指定用户的接口分类列表
     * @param $memberId
     * @param int $parentId
     * @return array|static[]
     */
    public static function getApiClassifyList($memberId, $parentId = 0)
    {
        $query = DB::table('request_share as share')
            ->select(['classify.*','share.member_id as uid','share.role'])
            ->leftJoin('request_folder as classify','share.classify_id','=','classify.classify_id');

        if($parentId > 0){
            $share = RequestShare::where('classify_id','=',$parentId)->where('member_id','=',$memberId);
            if(empty($share)){
                return null;
            }
        }else{
            $query = $query->where('share.member_id','=',$memberId);
        }
        $result = $query
            ->where('classify.parent_id','=',$parentId)
            ->orderBy('classify.classify_sort','DESC')
            ->get();

        return $result;
    }

    /**
     * 获取指定用户所有的分类
     * @param $memberId
     * @return array|static[]
     */

    public static function getApiClassifyAllList($memberId)
    {
        $result = DB::table('request_folder as classify')
            ->select(['classify.*','share.member_id as uid','share.role'])
            ->leftJoin('request_share as share','share.classify_id','=','classify.classify_id')
            ->where('share.member_id','=',$memberId)
            ->where('classify.parent_id','=',0)
            ->orderBy('classify.classify_sort','DESC')
            ->get();

        if(empty($result) === false){
            $parentIds = [];
            foreach ($result as $item){
                $parentIds[] = $item->classify_id;
            }
            $classifyList = DB::table('request_folder as classify')
                ->select(['classify.*'])
                ->whereIn('classify.parent_id',$parentIds)
                ->orderBy('classify.classify_sort','DESC')
                ->get();
            if(empty($classifyList) === false){
                foreach ($classifyList as &$item){
                    foreach ($result as $value){
                        if($item->parent_id === $value->classify_id){
                            $item->role = $value->role;
                        }
                    }
                }
                $result = array_merge($result,$classifyList);
            }
        }

        return $result;
    }


    /**
     * 判断是否存在编辑权限
     * @param $member_id
     * @param $classify_id
     * @return bool
     */
    public static function isHasEditRole($member_id,$classify_id)
    {
        $classifyId = intval($classify_id);
        if($classifyId <= 0){
            return false;
        }

        $share = RequestFolder::find($classifyId);

        if(empty($share)){
            return false;
        }
        if($share->parent_id !== 0){
            $share =  RequestFolder::find($share->parent_id);
            if(empty($share)){
                return false;
            }
            return RequestShare::where('classify_id','=',$share->classify_id)->where('member_id','=',$member_id)->exists();
        }

        return RequestShare::where('member_id','=',$member_id)
            ->where('classify_id','=',$classifyId)
            ->exists();
    }

    /**
     * 获取指定用户对指定目录的角色
     * @param int $member_id
     * @param int $classify_id
     * @return bool|int|mixed
     */
    public static function getRequestFolderRole($member_id,$classify_id)
    {
        $memberId = intval($member_id);
        //如果是超级管理员，则默认为拥有者
        if(Member::isSuperMember($memberId)){
            return 0;
        }
        $classifyId = intval($classify_id);
        if($classifyId <= 0){
            return false;
        }

        $share = RequestFolder::find($classifyId);

        if(empty($share)){
            return false;
        }
        if($share->parent_id !== 0){
            $share =  RequestFolder::find($share->parent_id);
            if(empty($share)){
                return false;
            }
            $share = RequestShare::where('classify_id','=',$share->classify_id)->where('member_id','=',$member_id)->first();

            return empty($share) ?  false : $share->role;
        }

        $share = RequestShare::where('member_id','=',$member_id)
            ->where('classify_id','=',$classifyId)
            ->first();

        return empty($share) ?  false : $share->role;
    }

    /**
     * 更新和统计指定分类的接口数量
     *
     * @param $classifyId
     */
    public static function updateRequestCount($classifyId)
    {
        $classify = RequestFolder::find($classifyId);

        $nodeFun = function ($classifyId){
            $subNodeId = RequestFolder::where('parent_id','=',$classifyId)->get(['classify_id']);

            $nodeIds = [];
            foreach ($subNodeId as $item){
                $nodeIds[] = intval($item['classify_id']);
            }
            $nodeIds[] = $classifyId;
            return RequestModel::whereIn('classify_id',$nodeIds)->count();
        };

        if(empty($classify) === false){
            if($classify->parent_id === 0){
                $classify->api_count = $nodeFun($classifyId);
            }else{
                $classify->api_count = RequestModel::where('classify_id','=',$classifyId)->count();

                $topClassify = RequestFolder::find($classify->parent_id);
                $topClassify->api_count = $nodeFun($classify->parent_id);
                $topClassify->save();

            }
            $classify->save();
        }
    }
}