<?php

namespace App\Observers;

use App\Models\Topic;
use App\Tool\SlugTranslate;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic)
    {
        // 对用户内容进行过滤。防跨站脚本攻击
        $topic->body = clean($topic->body, 'user_topic_body');

        // 生成话题摘录
        $topic->excerpt = make_excerpt($topic->body);

        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if ( ! $topic->slug) {
            $topic->slug = app(SlugTranslate::class)->translate($topic->title);
        }
    }
    // 删除帖子的时候同时删除所有回复
    public function deleted(Topic $topic)
    {
        \DB::table('replies')->where('topic_id',$topic->id)->delete();
    }
}