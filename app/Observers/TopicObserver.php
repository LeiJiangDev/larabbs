<?php

namespace App\Observers;

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
        $topic->body = clean($topic->body,'user_topic_body');
        //make_excerpt 自定义辅助函数（bootstrap/helpers.php中添加）
        $topic->excerpt = make_excerpt($topic->body);

    }
}