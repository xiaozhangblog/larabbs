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
        $topic = $reply->topic;
        //帖子回复数 + 1
        $topic->increment('reply_count',1);

        // XSS 过滤
        $reply->content = clean($reply->content,'user_topic_body');

        // 通知作者帖子被回复了
        $topic->user->notify(new TopicReplied($reply));
    }

    public function updating(Reply $reply)
    {
        //
    }
    // 帖子回复数减 1
    public function deleted(Reply $reply)
    {
        $reply->topic->decrement('reply_count',1);
    }
}