<?php

namespace SmartWiki\Models;

/**
 * SmartWiki\Models\Attachment
 *
 * @mixin \Eloquent
 * @property integer $attachment_id 主键
 * @property integer $doc_id 文档ID
 * @property string $file_name 文件名称
 * @property float $file_size 文件大小
 * @property string $create_time 创建日期
 * @property integer $create_at 上传人
 * @property string $comment 备注
 * @method static \Illuminate\Database\Query\Builder|Attachment whereAttachmentId($value)
 * @method static \Illuminate\Database\Query\Builder|Attachment whereDocId($value)
 * @method static \Illuminate\Database\Query\Builder|Attachment whereFileName($value)
 * @method static \Illuminate\Database\Query\Builder|Attachment whereFileSize($value)
 * @method static \Illuminate\Database\Query\Builder|Attachment whereCreateTime($value)
 * @method static \Illuminate\Database\Query\Builder|Attachment whereCreateAt($value)
 * @method static \Illuminate\Database\Query\Builder|Attachment whereComment($value)
 */
class Attachment extends ModelBase
{
    protected $table = 'attachment';
    protected $primaryKey = 'attachment_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['attachment_id'];

    public $timestamps = false;
}
