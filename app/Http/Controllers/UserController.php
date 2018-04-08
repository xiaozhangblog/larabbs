<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Tool\ImageUpload;

class UserController extends Controller
{
    // 用户个人页
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    // 用户数据修改展示页
    public function edit(User $user)
    {
        // authorize 方法来检验用户是否授权
        $this -> authorize('update',$user);
        return view('users.edit',compact('user'));
    }
    // 更新用户数据
    public function update(UserRequest $request,ImageUpload $upload, User $user)
    {
        // authorize 方法来检验用户是否授权
        $this -> authorize('update',$user);
        $data = $request -> all();
        // 处理上传图片保存并返回路径
        if($request -> avatar){
            $result = $upload ->save($request -> avatar,'avatar',$user ->id,362);
            if($result){
                $data['avatar'] = $result['path'];
            }
        }
        $user -> update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }

}