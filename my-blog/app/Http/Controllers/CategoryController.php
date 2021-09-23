<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Session;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private $categoryModel;

    public function __construct(Category $category)
    {
        $this->categoryModel = $category;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories = (new \App\Models\Category)->getCategoryByPaginate(20);
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     * @throws ValidationException
     */
    public function create()
    {
        return view('admin.category.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryStoreRequest $request
     * @return Response
     */
    public function store(CategoryStoreRequest $request)
    {
        $this->categoryModel->create($request->validated(), [
            'slug' => Str::slug($request->name, '-'),
            'description' => $request->description,
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryUpdateRequest $request
     * @param int $id
     * @return Response
     */
    public function update(CategoryUpdateRequest $request, $id)
    {

        $this->categoryModel->create($request->validated(),[
            'slug' => Str::slug($request->name, '-'),
            'description' => $request->description,
        ]);

        $this->categoryModel->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if($this->categoryModel){
            $this->categoryModel->delete();

            return redirect()->route('category.index');
    }
    }
}
