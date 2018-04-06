<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Traits\ActiveUserHelper;
    use Notifiable{
        notify as protected laravelNotify;
    }

    public function notify($instance)
    {
        //如果要通知的人是当前用户，就不必通知了
        if ($this->id == Auth::id()){
            return;
        }

        $this->increment('notification_count');
        $this->laravelNotify($instance);

    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','introduction','avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //模型关联
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    //授权
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    //清楚已读消息
    public function markAsRead()
    {
        $this ->notification_count = 0;
        $this->save();

        $this->unreadNotifications->markAsRead();

    }


    //修改器（后台修改密码时加密）
    public function setPasswordAttribute($value)
    {
        //如果值的长度等于60，即认为是已经做过加密的情况
        if (strlen($value) != 60){

            //长度不等于60，则进行加密处理
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;

    }

    //修改器（后台修改头像时拼接地址）
    public function setAvatarAttribute($path)
    {
        //如果不是http子串开头，则是从后台上传的头像
        if (!starts_with($path,'http')){

            //拼接完成的URL
            $path = config('app.url')."/uploads/images/avatar/$path";
        }

        $this ->attributes['avatar']= $path;

    }


}
