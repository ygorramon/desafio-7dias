<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Category;
use App\Models\Client;
use Helper;
class AnswerController extends Controller
{
 
    private $repository, $category;

    public function __construct (Answer $answer, Category $category){
        $this->repository = $answer;
        $this->category=$category;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
       
        if (!$category = $this->category->where('id', $id)->first()) {
            return redirect()->back();
        }

        $answers= $category->answers()->get();
        return view ('admin.pages.answers.index',[
            'answers'=>$answers,
            'category'=>$category
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        if (!$category = $this->category->where('id', $id)->first()) {
            return redirect()->back();
        }

        return view('admin.pages.answers.create', [
            'category' => $category,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        if (!$category = $this->category->where('id', $id)->first()) {
            return redirect()->back();
        }

        $category->answers()->create($request->all());

        return redirect()->route('situacoes.respostas.index', $category->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function categories()
    {
        /*
$client=Client::find(1);
 
        $categories =$this->category->where('sex',$client->babySex)->get();
     foreach($categories as $category){
         foreach($category->answers as $answer){
             echo $answer->id.' '. Helper::stringReplace($answer->response,$client).'<br><br><br><br>';
         }
     }
    */
        $categories= Category::all();
        return view ('admin.pages.answers.categories',[
            'categories'=>$categories
        ]);
        

    }
}
