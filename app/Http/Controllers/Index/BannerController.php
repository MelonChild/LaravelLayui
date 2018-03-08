<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Index\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\News\Banner;

class BannerController extends BaseController {

    /**
     * 首页
     *
     * @return 
     */
    public function index(Request $request) {

        $search = $request->input('search', '');
        $selectId = $request->input('selectId', '');

        $datas = Banner::where('name', 'like', '%' . $search . '%')->where(function($query) use ($selectId) {
                    if ($selectId) {
                        $query->where('cate_id', $selectId);
                    }
                })->orderBy('sort')->paginate(12);
        $datas->appends(compact('search'))->render();

        //查找所有分类
        $cates = \App\Models\News\Cate::get();

        return view('index.banner.index')->with(['datas' => $datas, 'search' => $search, 'selectId' => $selectId, 'cates' => $cates]);
    }

    /**
     * 创建
     *
     * @return 
     */
    public function create(Request $request) {
        //查找所有分类
        $cates = \App\Models\News\Cate::get();
        return view('index.banner.add')->with(['cates' => $cates]);
    }

    /**
     * 编辑
     *
     * @return 
     */
    public function edit(Request $request, $id) {
        $cates = \App\Models\News\Cate::get();
        $banner = Banner::find($id);
        return view('index.banner.edit')->with(['cates' => $cates, 'detail' => $banner]);
    }

    /**
     * 存储
     *
     * @return 
     */
    public function store(Request $request) {
        $input = $request->except('_token');

        //查看文章是否存在
        $article = \App\Models\News\Article::find($input['article_id']);
        $input['article_id'] = $article ? $input['article_id'] : 1;

        //迁移图片
        $oldname = $input['path'];
        $new_file = "uploads/banner/" . date('Ymd', time()) . "/";
        if (!file_exists('./' . $new_file)) {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir('./' . $new_file, 0777);
        }
        $newname = str_replace('temp', 'banner', $oldname);
        rename('.' . $oldname, '.' . $newname);
        $input['path'] = $newname;

        $result = Banner::create($input);
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
        //查看文章是否存在
        $article = \App\Models\News\Article::find($input['article_id']);
        $input['article_id'] = $article ? $input['article_id'] : 1;

        //迁移图片
        $oldname = $request->input('oldname');

        if ($oldname != $input['path']) {
            $new_file = "uploads/banner/" . date('Ymd', time()) . "/";
            if (!file_exists('./' . $new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir('./' . $new_file, 0777);
            }
            $newname = str_replace('temp', 'banner', $input['path']);
            rename('.' . $input['path'], '.' . $newname);
            $input['path'] = $newname;
            file_exists('./' . $oldname) && unlink('.' . $oldname);
        }

        $result = Banner::where('id', $id)->update($input);
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
            $picture = Banner::find($id);
            $result = Banner::where('id', $id)->delete();
        }

        if ($result) {
            file_exists('.' . $picture['path']) && unlink('.' . $picture['path']);
            return response()->json(['status' => true, 'hint' => '删除成功！']);
        } else {
            return response()->json(['status' => false, 'hint' => '删除失败！']);
        }
    }

}
