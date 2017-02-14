<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/2/14 0014
 * Time: 17:11
 */

namespace SmartWiki\Models;

/**
 * 接口参与列表
 * @property int $share_id
 * @property int $classify_id
 * @property int $member_id
 * @property int $role
 * Class ApiShoare
 * @package SmartWiki\Models
 */
class ApiShare extends ModelBase
{
    protected $table = 'api_share';
    protected $primaryKey = 'share_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['share_id'];

    public $timestamps = false;

}