<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Index\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController {

    /**
     * 首页
     *
     * @return 
     */
    public function info(Request $request) {
        $user = Auth::guard()->user();
        //保存信息
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            //判断是否修改密码
            if ($request->has('oldpwd')) {
                if (Hash::check($input['oldpwd'], $user['password'])) {
                    unset($input['oldpwd']);
                    $input['password'] = bcrypt($input['password']);
                } else {
                    $return['errno'] = true;
                    $return['message'] = '旧密码不正确';
                    return response()->json($return);
                }
            }
            $return['errno'] = true;
            $return['message'] = '更新失败，请稍候重试';
            $result = User::where('id', $user->id)->update($input);

            if ($result !== false) {
                $return['errno'] = false;
                $return['message'] = '更新成功';
            }
            return response()->json($return);
        }

        $userInfo = User::find($user['id']);
        return view('index.user.info',  compact('userInfo'));
    }

    /**
     * 修改密码
     *
     * @return 
     */
    public function pwd(Request $request) {
        return view('index.user.pwd');
    }

    /**
     * 存储头像
     *
     * @return 
     */
    public function avatarStore(Request $request) {
        $return['errno'] = true;
        $return['message'] = "上传失败";

        //保存信息
        if ($request->isMethod('post')) {
            $base64_image_content = $request->input('file');
            if ($base64_image_content) {

                //匹配出图片的格式
                if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                    $type = $result[2];
                    $new_file = "/uploads/avatar/" . date('Ymd', time()) . "/";
                    if (!file_exists('.' . $new_file)) {
                        //检查是否有该文件夹，如果没有就创建，并给予最高权限
                        mkdir('.' . $new_file, 0777);
                    }
                    $new_file = $new_file . time() . "." . $type;

                    if (file_put_contents('.' . $new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                        $return['errno'] = false;
                        $return['message'] = "上传成功";
                        $return['path'] = "$new_file";
                    }
                }
            }
        }
        return $return;
    }

    /**
     * 首页
     *
     * @return 
     */
    public function index(Request $request) {

        $search = $request->input('search', '');

        $datas = User::where('username', 'like', '%' . $search . '%')->paginate(12);
        $datas->appends(compact('search'))->render();

        return view('index.user.index')->with(['datas' => $datas, 'search' => $search]);
    }

    /**
     * 创建
     *
     * @return 
     */
    public function create(Request $request) {
        $roles = \App\Models\Role::get();
        return view('index.user.add')->with(['roles' => $roles]);
    }

    /**
     * 编辑
     *
     * @return 
     */
    public function edit(Request $request, $id) {
        $roles = \App\Models\Role::get();
        $user = User::find($id);
        $roleIds = $user->roles()->pluck('id')->toArray();
        return view('index.user.edit')->with(['roles' => $roles, 'user' => $user, 'roleIds' => $roleIds]);
    }

    /**
     * 存储
     *
     * @return 
     */
    public function store(Request $request) {
        $input = $request->except('_token', 'roles');

        //用户
        $user = User::where('username', $input['username'])->first();

        if ($user) {
            $return['errno'] = true;
            $return['message'] = "用户名重复";
        } else {
            $input['password'] = bcrypt('123456');
            $input['active'] =$request->input('active')?1:0;
            $result = User::create($input);
            if ($result) {
                $roles = $request->input('roles');
                User::where('id',$result['id'])->first()->roles()->sync($roles);
                $return['errno'] = false;
                $return['message'] = "添加成功";
            } else {
                $return['errno'] = true;
                $return['message'] = "添加失败";
            }
        }
        return $return;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $input = $request->except('_token', '_method', 'roles', 'password');
        //用户
        $user = User::where('username', $input['username'])->where('id', '<>', $id)->first();

        if ($user) {
            $return['errno'] = true;
            $return['message'] = "用户名重复";
        } else {
            if ($request->input('password')) {
                $input['password'] = bcrypt($request->input('password'));
            }
            $input['active'] =$request->input('active')?1:0;

            $result = User::where('id',$id)->update($input);
            if ($result) {
                $roles = $request->input('roles');
                User::where('id',$id)->first()->roles()->sync($roles);
                $return['errno'] = false;
                $return['message'] = "添加成功";
            } else {
                $return['errno'] = true;
                $return['message'] = "添加失败";
            }
        }
        return $return;
    }

    /**
     * 用户名查重
     *
     * @return 
     */
    public function checkUsername(Request $request) {
        $input = $request->except('_token');

        //用户
        $user = User::where('username', $input['username'])->where('id', '<>', $input['id'])->first();

        if ($user) {
            $return['errno'] = true;
            $return['message'] = "用户名重复";
        } else {

            $return['errno'] = false;
            $return['message'] = "添加失败";
        }
        return $return;
    }

}
