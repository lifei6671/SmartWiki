<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/2/14 0014
 * Time: 11:32
 */

namespace SmartWiki\Models;

/**
 * API储存表
 * @property integer $api_id 主键
 * @property integer $doc_id 关联文档
 * @property integer $classify_id 接口分类
 * @property string $api_name 接口名称
 * @property string $description
 * @property string $method
 * @property string $member_id
 * @property string $request_url
 * @property string $authorization_classify
 * @property string $authorization
 * @property string $headers
 * @property string $body
 * @property string $raw_data
 * @property string $enctype
 * @property string $create_time
 * @property int $create_at
 * @property int $sort
 * Class ApiModel
 * @package SmartWiki
 */
class RequestModel extends ModelBase
{
    protected $table = 'requests';
    protected $primaryKey = 'api_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['api_id'];

    public $timestamps = false;


}