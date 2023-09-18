<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Http\Requests\BlogRequest;

class BlogController extends Controller
{
    /**
     * ブログ一覧を表示する
     *
     */
    public function showList()
    {
        $blogs = Blog::all();

        return view('blog.list',['blogs' => $blogs]);
    }

    /**
     * ブログ詳細を表示する
     *@param $id
     */
    public function showDetail($id)
    {
        $blog = Blog::find($id);

        if(is_null($blog)){
            \Session::flash('err_msg','データがありません');
            return redirect(route('blogs'));
        }

        return view('blog.detail',['blog' => $blog]);
    }

    /**
     * ブログの登録画面を表示する
     */
    public function showCreate(){
        return view('blog.form');
    }

    /**
     * ブログを登録を表示する
     */
    public function exeStore(BlogRequest $request){

        // ブログのデータを受け取る
        $input = $request-> all();

        \DB::beginTransaction();
        try{
            Blog::create($input);
            \DB::commit();
        }catch(\Throwable $e){
            \DB::rollBack();
            abort(500);
        }

        \Session::flash('err_msg','ブログを登録しました。');
        return redirect(route('blogs'));

    }

    /**
     * ブログ編集フォームを表示する
     *@param $id
     */
    public function showEdit($id)
    {
        $blog = Blog::find($id);

        if(is_null($blog)){
            \Session::flash('err_msg','データがありません');
            return redirect(route('blogs'));
        }

        return view('blog.edit',['blog' => $blog]);
    }

    /**
     * ブログを更新を表示する
     */
    public function exeUpdate(BlogRequest $request){

        // ブログのデータを受け取る
        $input = $request-> all();

        \DB::beginTransaction();
        try{
            $blog =Blog::find($input['id']);
            $blog->fill([
                'title'=>$input['title'],
                'content'=>$input['content'],
            ]);
            $blog->save();
            \DB::commit();
        }catch(\Throwable $e){
            \DB::rollBack();
            abort(500);
        }

        \Session::flash('err_msg','ブログを更新しました。');
        return redirect(route('blogs'));
    }

    /**
     * ブログ編集フォームを表示する
     *@param $id
     */
    public function exeDelete($id)
    {

        if(empty($id)){
            \Session::flash('err_msg','データがありません');
            return redirect(route('blogs'));
        }
        // dd($id);
        try{
            // ブログの削除
            Blog::destroy($id);
        }catch(\Throwable $e){
            abort(500);
        }

        \Session::flash('err_msg','削除しました');
        return redirect(route('blogs'));
}






}
