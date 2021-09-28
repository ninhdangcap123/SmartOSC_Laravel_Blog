<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagsStoreRequest;
use App\Http\Requests\TagsUpdateRequest;
use App\Models\Tags;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TagsController extends Controller
{
    private $tagModel;

    public function __construct(Tags $tag)
    {
        $this->tagModel = $tag;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = (new \App\Models\Tags)->getTagsByPaginate(20);
        return view('admin.tag.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tag.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(TagsStoreRequest $request)
    {

        $this->tagModel->create($request->validated(), [
            'slug' => Str::slug($request->name, '-'),
            'description' => $request->description,
        ]);

        return redirect()->back();
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function edit($id)
    {
        return view('admin.tag.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(TagsUpdateRequest $request, $id)
    {

        $this->tagModel->create($request->validated(), [
            'slug' => Str::slug($request->name, '-'),
            'description' => $request->description,
        ]);


        $this->tagModel->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->tagModel){
            $this->tagModel->delete();
        }

        return redirect()->back();
    }
}
