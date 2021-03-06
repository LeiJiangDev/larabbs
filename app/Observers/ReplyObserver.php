<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        //防止xss攻击
        $reply->content = clean($reply->content,'user_topic_body');

    }

    public function updating(Reply $reply)
    {
        //
    }


    public function created(Reply $reply)
    {
        $topic = $reply->topic;
        //帖子回复数量+1
        $reply ->topic ->increment('reply_count',1);

        //通知作者话题被回复了
        $topic->user->notify(new TopicReplied($reply));

    }

    public function deleted(Reply $reply)
    {
        //帖子数-1
        $reply->topic->decrement('reply_count',1);

    }

}