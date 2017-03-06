<?php

namespace SmartWiki\Models;

use DB;

/**
 * SmartWiki\Models\DocumentHistory
 *
 * @mixin \Eloquent
 * @property integer $history_id
 * @property integer $doc_id 文档ID
 * @property string $doc_name 文档名称
 * @property integer $parent_id 父ID
 * @property string $doc_content 文档内容
 * @property string $modify_time
 * @property integer $modify_at
 * @property string $version 当前时间戳
 * @method static \Illuminate\Database\Query\Builder|DocumentHistory whereHistoryId($value)
 * @method static \Illuminate\Database\Query\Builder|DocumentHistory whereDocId($value)
 * @method static \Illuminate\Database\Query\Builder|DocumentHistory whereDocName($value)
 * @method static \Illuminate\Database\Query\Builder|DocumentHistory whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|DocumentHistory whereDocContent($value)
 * @method static \Illuminate\Database\Query\Builder|DocumentHistory whereModifyTime($value)
 * @method static \Illuminate\Database\Query\Builder|DocumentHistory whereModifyAt($value)
 * @method static \Illuminate\Database\Query\Builder|DocumentHistory whereVersion($value)
 * @property string $create_time 历史记录创建时间
 * @property integer $create_at 历史记录创建人
 * @method static \Illuminate\Database\Query\Builder|DocumentHistory whereCreateTime($value)
 * @method static \Illuminate\Database\Query\Builder|DocumentHistory whereCreateAt($value)
 */
class DocumentHistory extends ModelBase
{
    protected $table = 'document_history';
    protected $primaryKey = 'history_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['history_id'];

    public $timestamps = false;

    /**
     * 获取指定文档的历史版本
     * @param int $doc_id
     * @param int $pageIndex
     * @param int $pageSize
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getDocumentHistoryByDocumentId($doc_id,$pageIndex = 1, $pageSize = 20)
    {
        $query = DB::table('document_history AS history')
            ->select(['history.*','member.account'])
            ->leftJoin('member','history.create_at','=','member.member_id')
            ->where('history.doc_id','=',$doc_id)
            ->orderBy('history.history_id','DESC')
            ->paginate($pageSize,['*'],'page',$pageIndex);

        return $query;
    }
}
