<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Nav extends Authenticatable
{
    use Notifiable;

    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'navs';


    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $guarded = [];
   
    /**
     * 角色权限
     */
    public function roles() {
        return $this->belongsToMany('App\Models\Role','role_nav');
    }

}
