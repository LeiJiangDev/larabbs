<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    //根据分类显示
    public function show(Category $category,Request $request,Topic $topic,User $user)
    {
        //读取分类ID关联的话题，并按每20条分页
        $topics = $topic->WithOrder($request->order)
            ->where('category_id',$category->id)
            ->paginate(20);

        //获取活跃用户列表
        $active_users = $user->getActiveUsers();
        //传参变量话题和分类到模板中
        return view('topics.index',compact('topics','category','active_users'));

    }
}
