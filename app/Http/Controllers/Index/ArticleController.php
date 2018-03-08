<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Index\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\News\Article;

class ArticleController extends BaseController {

    /**
     * 首页
     *
     * @return 
     */
    public function index(Request $request) {

        $search = $request->input('search', '');
        $selectId = $request->input('selectId', '');

        $datas = Article::where('title', 'like', '%' . $search . '%')->where(function($query) use ($selectId) {
                    if ($selectId) {
                        $query->where('cate_id', $selectId);
                    }
                })->orderBy('id','desc')->paginate(12);
        $datas->appends(compact('search'))->render();

        //查找所有分类
        $cates = \App\Models\News\Cate::get();

        return view('index.article.index')->with(['datas' => $datas, 'search' => $search, 'selectId' => $selectId, 'cates' => $cates]);
    }

    /**
     * 创建
     *
     * @return 
     */
    public function create(Request $request) {
        //查找所有分类
        $cates = \App\Models\News\Cate::get();
        return view('index.article.add')->with(['cates' => $cates]);
    }

    /**
     * 编辑
     *
     * @return 
     */
    public function edit(Request $request, $id) {
        $cates = \App\Models\News\Cate::get();
        $article = Article::find($id);
        return view('index.article.edit')->with(['cates' => $cates, 'detail' => $article]);
    }

    /**
     * 存储
     *
     * @return 
     */
    public function store(Request $request) {
        $input = $request->except('_token');

        //迁移图片
        $oldname = $input['thumbnail'];
        $new_file = "uploads/article/" . date('Ymd', time()) . "/";
        if (!file_exists('./' . $new_file)) {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir('./' . $new_file, 0777);
        }
        $newname = str_replace('temp', 'article', $oldname);
        rename('.' . $oldname, '.' . $newname);
        $input['thumbnail'] = $newname;

        $result = Article::create($input);
        if ($result) {
            $return['errno'] = false;
            $return['message'] = "添加成功";
        } else {
            $return['errno'] = true;
            $return['message'] = "添加失败";
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
        $input = $request->except('_token', '_method', 'oldname');

        //迁移图片
        $oldname = $request->input('oldname');

        if ($oldname != $input['thumbnail']) {
            $new_file = "uploads/article/" . date('Ymd', time()) . "/";
            if (!file_exists('./' . $new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir('./' . $new_file, 0777);
            }
            $newname = str_replace('temp', 'article', $input['thumbnail']);
            rename('.' . $input['thumbnail'], '.' . $newname);
            $input['thumbnail'] = $newname;
            file_exists('./' . $oldname) &&unlink('.' . $oldname);
        }

        $result = Article::where('id', $id)->update($input);
        if ($result !== false) {

            $return['errno'] = false;
            $return['message'] = "修改成功";
        } else {
            $return['errno'] = true;
            $return['message'] = "修改失败";
        }
        return $return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $result = false;
        if ($id) {
            $picture = Article::find($id);
            $result = Article::where('id', $id)->delete();
        }

        if ($result) {
            file_exists('.' . $picture['thumbnail']) && unlink('.' . $picture['thumbnail']);
            return response()->json(['status' => true, 'hint' => '删除成功！']);
        } else {
            return response()->json(['status' => false, 'hint' => '删除失败！']);
        }
    }

    /**
     * 生成图片
     *
     * @return 
     */
    public function uploadCropPic(Request $request) {
        $return['errno'] = true;
        $return['message'] = "上传失败";
        $input = $request->input();
        //保存信息
        if ($request->isMethod('post')) {
            $base64_image_content = $request->input('file');
            if ($base64_image_content) {

                //匹配出图片的格式
                if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                    $type = $result[2];
                    $new_file = "uploads/temp/" . date('Ymd', time()) . "/";
                    if (!file_exists('./' . $new_file)) {
                        //检查是否有该文件夹，如果没有就创建，并给予最高权限
                        mkdir('./' . $new_file, 0777);
                    }
                    $new_file = $new_file . time() . "." . $type;
                    if (file_put_contents('./' . $new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                        //生成图片
                        $return['errno'] = false;
                        $return['message'] = "上传成功";
                        $return['path'] = '/' . $new_file;
                    }
                }
            }
        }
        return $return;
    }

}
