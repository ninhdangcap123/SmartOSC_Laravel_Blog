<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tags;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $articles = (new \App\Models\Article)->getArticlesByPaginate(20);
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
     * @param Request $request
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:articles,title',
            'image' => 'required|image',
            'description' => 'required',
            'category' => 'required',
        ]);

        $article = Article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'image' => 'image.jpg',
            'description' => $request->description,
            'category_id' => $request->category,
            'user_id' => auth()->user()->id,
            'published_at' => Carbon::now(),
        ]);

        $article = (new \App\Models\Article)->attachTagsToArticles($request->tags);

        if ($request->hasFile('image')) {

            $article->image = Storage::putFile('image', $request->file('image'));

        }

        $article->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Model\Article $article
     * @return Response
     */
    public function show($id)
    {
        return view('admin.article.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Model\Article $article
     * @return Response
     */
    public function edit($id)
    {
        $tags = (new App\Models\Tags)->all();
        $categories = (new App\Models\Category)->all();
        return view('admin.article.edit', compact(['article', 'categories', 'tags']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param \App\Model\Article $article
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => "required|unique:articles,title, $article->id",
            'description' => 'required',
            'category' => 'required',
        ]);

        $article->title = $request->title;
        $article->slug = Str::slug($request->title);
        $article->description = $request->description;
        $article->category_id = $request->category;

        $article = (new \App\Models\Article)->syncTagsToArticles($request->tags);

        if ($request->hasFile('image')) {

            $article->image = Storage::putFile('image', $request->file('image'));
        }

        $article->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Model\Article $article
     * @return Response
     */
    public function destroy($id)
    {
        if ($article) {
            if (file_exists(public_path($article->image))) {
                unlink(public_path($article->image));
            }

            $article->delete();
        }

        return redirect()->back();
    }
}
