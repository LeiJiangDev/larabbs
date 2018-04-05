<?php

namespace App\Observers;

use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;
use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic)
    {
        //XSS过滤
        $topic->body = clean($topic->body,'user_topic_body');

        //make_excerpt 自定义辅助函数（bootstrap/helpers.php中添加）
        //生成话题摘录
        $topic->excerpt = make_excerpt($topic->body);



    }

    public function saved(Topic $topic)
    {
        //如果slug字段无内容，即使用翻译器对title进行翻译
        if (! $topic ->slug){
            //修改为队列任务
            //$topic -> slug = app(SlugTranslateHandler::class)->translate($topic->title);

            //推送任务到队列
            dispatch(new  TranslateSlug($topic));
        }
    }
}