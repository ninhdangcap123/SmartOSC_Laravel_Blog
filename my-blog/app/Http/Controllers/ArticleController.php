<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Session;


class ArticleController extends Controller
{
    private $articleModel;

    public function __construct(Article $article)
    {
        $this->articleModel = $article;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $articles = (new Article)->getArticlesByPaginate(20);
        return view('admin.article.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $tags = (new App\Models\Tags)->all();
        $categories = (new App\Models\Category)->all();
        return view('admin.article.create', compact(['categories', 'tags']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return Response
     */
    public function store(StoreRequest $request)
    {
        $this->articleModel->create($request->validated(), [
            'slug' => Str::slug($request->title),
            'user_id' => auth()->user()->id,
            'published_at' => Carbon::now(),
        ]);

        $this->articleModel->attachTagsToArticles($request->tags);

        if ($request->hasFile('image')) {

            $this->uploadFile($request->validated(['image']));
        }
        $this->articleModel->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        return view('admin.article.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function edit($id)
    {
        $tags = (new App\Models\Tags)->all();
        $categories = (new App\Models\Category)->all();
        return view('admin.article.edit', compact(['article', 'categories', 'tags']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {

        if ($this->articleModel) {
            if (file_exists(public_path($this->articleModel->image))) {
                unlink(public_path($this->articleModel->image));
            }

            $this->articleModel->delete();
        }

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     * @paraw int $id
     * @param UpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function update(UpdateRequest $request, $id)
    {

        $this->articleModel->create($request->validated());

        $this->articleModel->syncTagsToArticles($request->tags);

        if ($request->hasFile('image')) {

            $this->uploadFile($request->validated(['image']));
        }

        $this->articleModel->save();
        return redirect()->back();
    }
}
