<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

/**
 * 后台增删改查控制器
 * 
 * @author liuwenhai
 *        
 */
class CurdController extends BaseController{
    
    // 对应的模型
    protected $model;
    protected $controller;

    public function __construct(){
        parent::__construct();
        
        //控制器
        $route = Route::currentRouteName();
        
        list($moudle,$this->controller,$action) = explode('.', $route);

        View::share('controller', $this->controller);
        //模型
        if(!$this->model){
            $model = 'App\Models\\'.ucfirst($this->controller);
            $this->model = new $model;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $input = $request->except('_token', '_method');
        
        $search = isset($input['search'])?$input['search']:'';
        // 存在搜索条件,则追加分页参数
        $datas = $this->model->where(function ($query) use($search){
            if ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            }
        })->paginate(10);
        $datas->appends(compact('searchWord'))->render();
        
        $title = '列表';
        return view('admin.' . strtolower($this->controller) . '.index', compact('datas', 'title','search'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('admin.' . $this->controller . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $input = $request->except('_token', '_method','file');
        $result = $this->model->create($input);
        if ($result) {
            return redirect(route('admin.' . $this->controller . '.index'))->with('hint', '数据填充成功！');
        } else {
            return redirect(route('admin.' . $this->controller . '.index'))->with('hint', '数据填充失败，请稍后重试！');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $field = $this->model->find($id);
        $title = '详情';
        return view('admin.' . $this->controller . '.show', compact('field', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $field = $this->model->find($id);
        $title = '修改';
        return view('admin.' . $this->controller . '.edit', compact('field', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $input = $request->except('_token', '_method');
        $result = $this->model->where('id', $id)->update($input);
        if ($result !== false) {
            return response()->json(1);
        } else {
            return response()->json(0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $result = $this->model->where('id', $id)->delete();
        if ($result) {
            return response()->json(['status'=>true,'hint'=>'删除成功！']);
        } else {
            return response()->json(['status'=>false,'hint'=>'删除失败！']);
        }
    }
}
