<?php

namespace App\Observers;

use App\Models\Reply;

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

    //帖子回复数量+1
    public function created(Reply $reply)
    {

        $reply ->topic ->increment('reply_count',1);

    }

}