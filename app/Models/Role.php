<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Role extends Authenticatable
{
    use Notifiable;

    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'roles';


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
     * 用户角色
     */
    public function users() {
        return $this->belongsToMany('App\Models\User');
    }
    
    /**
     * 角色权限
     */
    public function navs() {
        return $this->belongsToMany('App\Models\Nav');
    }

}
