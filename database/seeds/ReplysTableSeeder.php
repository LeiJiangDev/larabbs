<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {

        //获取所有用户ID并转换成数组
        $user_ids = User::all() ->pluck('id')->toArray();

        //获取所有话题ID并转换成数组
        $topic_ids = Topic::all()->pluck('id')->toArray();

        //获取Faker实例
        $faker = app(Faker\Generator::class);

        $replys = factory(Reply::class)
            ->times(1500)
            ->make()
            ->each(function ($reply, $index)
            use ($user_ids,$topic_ids,$faker)
            {

                //随机获取一个用户id并赋值
            $reply->user_id  = $faker->randomElement($user_ids);

                //获取获取一个话题id并赋值
            $reply->topic_id = $faker->randomElement($topic_ids);

        });

        Reply::insert($replys->toArray());
    }

}

