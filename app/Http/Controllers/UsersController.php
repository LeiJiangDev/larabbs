<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',['except' => 'show']);
    }

    //显示用户页面
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }


    //编辑用户资料
    public function  edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }


    //更新用户资料
    public function update(UserRequest $request,ImageUploadHandler $uploader,User $user)
    {
        $this->authorize('update',$user);
        //dd($request->avatar);

        $data = $request->all();

        if ($request->avatar){
            $result = $uploader->save($request->avatar,'avatar',$user->id,362);
            //dd($result);exit;
            if ($request){

                //$result是一个数组
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);

        return redirect()->route('users.show',$user->id)->with('success','个人资料更新成功');

    }


}
