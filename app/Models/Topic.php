<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'category_id','excerpt','slug'];


    //关联分类
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //关联作者
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWithOrder($query,$order)
    {
        //不同的排序，使用不同的数据读取逻辑
        switch ($order){
            case 'recent':
                $query = $this->recent();
                break;

            default:
                $query = $this->recentReplied();
                break;

        }

        return $query->with('user','category');
    }

    //根据最后回复时间排序
    public function scopeRecentReplied($query)
    {
        return $query->orderBy('updated_at','desc');
    }

    //根据创建时间排序
    public function scopeRecent($query)
    {
       return $query->orderBy('created_at','desc');
    }

}
