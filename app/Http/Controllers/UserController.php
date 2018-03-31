<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;

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
        return view('users.edit',compact('user'));
    }
    // 更新用户数据
    public function update(UserRequest $request, User $user)
    {
        $user -> update($request -> all());
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }

}