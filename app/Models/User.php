<?php

namespace app\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    protected $guard_name = 'web';

    use Notifiable {
        notify as protected laravelNotify;
    }

    public function notify($instance)
    {
      // 如果要通知的人是当前用户，就不必通知了
        if($this ->id == Auth::id())
        {
            return false;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }
    // 去除未读消息标示
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
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
    // 用户模型中新增与话题模型的关联
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
    public function setPasswordAttribute($value)
    {
        // 如果长度等于 60 即认为已经做过加密的情况
        if(strlen($value) != 60){
            // 不等于 60 做密码加密处理
            $value = bcrypt($value);
        }
        $this->attributes['password'] = $value;
    }
    public function setAvatarAttribute($path)
    {
        // 如果不是 `http` 子串开头，那就是从后台上传的，需要补全 URL
        if(!starts_with($path,'http')){
            // 拼接完整的 URL
            $path = config('app.url')."/uploads/images/avatar/$path";
        }
        $this->attributes['avatar'] = $path;
    }
}
