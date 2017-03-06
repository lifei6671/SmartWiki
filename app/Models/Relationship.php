<?php

namespace SmartWiki\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * SmartWiki\Models\Relationship
 *
 * @mixin \Eloquent
 * @property integer $rel_id
 * @property integer $member_id
 * @property integer $project_id
 * @method static \Illuminate\Database\Query\Builder|Relationship whereRelId($value)
 * @method static \Illuminate\Database\Query\Builder|Relationship whereMemberId($value)
 * @method static \Illuminate\Database\Query\Builder|Relationship whereProjectId($value)
 * @property boolean $role_type 项目角色：0 参与者，1 所有者
 * @method static \Illuminate\Database\Query\Builder|Relationship whereRoleType($value)
 */
class Relationship extends ModelBase
{
    protected $table = 'relationship';
    protected $primaryKey = 'rel_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['rel_id'];

    public $timestamps = false;
}
