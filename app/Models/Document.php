<?php

namespace SmartWiki\Models;

use Cache;
use Carbon\Carbon;

/**
 * SmartWiki\Models\Document
 *
 * @mixin \Eloquent
 * @property integer $doc_id
 * @property string $doc_name 文档名称
 * @property integer $parent_id 父ID
 * @property integer $project_id 所属项目
 * @property integer $doc_sort 排序
 * @property string $doc_content 文档内容
 * @property string $create_time
 * @property integer $create_at
 * @property string $modify_time
 * @property integer $modify_at
 * @property string $version 当前时间戳
 * @method static \Illuminate\Database\Query\Builder|Document whereDocId($value)
 * @method static \Illuminate\Database\Query\Builder|Document whereDocName($value)
 * @method static \Illuminate\Database\Query\Builder|Document whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|Document whereProjectId($value)
 * @method static \Illuminate\Database\Query\Builder|Document whereDocSort($value)
 * @method static \Illuminate\Database\Query\Builder|Document whereDocContent($value)
 * @method static \Illuminate\Database\Query\Builder|Document whereCreateTime($value)
 * @method static \Illuminate\Database\Query\Builder|Document whereCreateAt($value)
 * @method static \Illuminate\Database\Query\Builder|Document whereModifyTime($value)
 * @method static \Illuminate\Database\Query\Builder|Document whereModifyAt($value)
 * @method static \Illuminate\Database\Query\Builder|Document whereVersion($value)
 */
class Document extends ModelBase
{
    protected $table = 'document';
    protected $primaryKey = 'doc_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['doc_id'];

    public $timestamps = false;

    /**
     * 从缓存中获取指定的文档
     * @param $doc_id
     * @param bool $update 是否强制更新缓存
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|Document|Document[]
     */
    public static function getDocumentFromCache($doc_id,$update = false)
    {
        $key = 'document.doc_id.'.$doc_id;
        $document = $update or Cache::get($key);

        if(empty($document) or $update){
            $document = Document::find($doc_id);
            $expiresAt = Carbon::now()->addHour(12);

            Cache::put($key,$document,$expiresAt);
        }
        return $document;
    }

    /**
     * 从换成中获取解析后的文档内容
     * @param int $doc_id
     * @param bool $update
     * @return bool|string
     */
    public static function getDocumnetHtmlFromCache($doc_id,$update = false)
    {
        $key = 'document.html.' . $doc_id;

        $html = null;//$update or Cache::get($key);

        if(empty($html)) {
            $document = self::getDocumentFromCache($doc_id, $update);

            if (empty($document)) {
                return false;
            }
            if(empty($document->doc_content)){
                return '';
            }
//            $parsedown = new \Parsedown();
//
//            $html  = $parsedown->text($document->doc_content);

            $html = markdown_converter($document->doc_content);

            $html = str_replace('class="language-','class="',$html);
            $expiresAt = Carbon::now()->addHour(12);

            Cache::put($key,$html,$expiresAt);
        }
        return $html;
    }

    public static function deleteDocument($doc_id)
    {
        $documents = [];
        $doc = Document::find($doc_id);
        if (empty($doc) === false) {
            $documents[] = $doc;
            $recursion = function ($id, $callback) use (&$documents) {
                $docs = Document::where('parent_id', '=', $id)->get();

                foreach ($docs as $doc) {
                    $documents[] = $doc;
                    $callback($doc->doc_id, $callback);
                }

            };
            $recursion($doc->doc_id, $recursion);
        }
        foreach ($documents as $document){
            $document->delete();
        }
        return true;
    }
}
