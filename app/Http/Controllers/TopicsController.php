<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        //对除了 index() 和 show() 以外的方法使用 auth 中间件进行认证。
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request,Topic $topic)
	{
		$topics = $topic->WithOrder($request->order)->paginate(20);
		return view('topics.index', compact('topics'));
	}

    public function show(Request $request,Topic $topic)
    {

        if (! empty($topic->slug) && $topic->slug != $request->slug){
            return redirect($topic->link(),301);
        }
        return view('topics.show', compact('topic'));
    }

    //创建帖子页面
	public function create(Topic $topic)
	{
	    $categories = Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	//将帖子保存到数据库
	public function store(TopicRequest $request,Topic $topic)
    {
        //获得所有表单数据
        $topic->fill($request->all());

        //获得当前登录用户ID
        $topic->user_id = Auth::id();
        $topic->save();

        return redirect()->to($topic->link())->with('message', 'Created successfully.');
    }

    //编辑话题
    public function edit(Topic $topic)
	{
	    //授权更新
        $this->authorize('update', $topic);

        //获取全部分类
        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	//更新话题
	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->to($topic->link())->with('message', 'Updated successfully.');
	}

	//删除话题
	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', 'Deleted successfully.');
	}

	//上传图片
    public function uploadImage(Request $request,ImageUploadHandler $uploader)
    {
        //初始化返回数据，默认失败
        $data = [
          'success'     =>  false,
          'msg'         =>  '上传失败',
          'file_path'   =>  ''
        ];

        //判断是否有图片上传，并赋值给$file
        if ($file = $request->upload_file){
            //保存图片到本地
            $result = $uploader -> save($request->upload_file,'topics',\Auth::id(),1024);

            //如果图片保存成功
          /*  if ($result){
                $data['file_path']  = $result['path'];
                $data['msg']        ="上传成功";
                $data['success']    =true;
            }*/

            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }

        return $data;
    }





}