<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model 
{
    use SoftDeletes;
    

    protected $fillable = ['user_id', 'title', 'article_text'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getCategoriesLinksAttribute()
    {
        $categories = $this->categories()->get()->map(function($category) 
        {
            $categoryID = $category->id;
            $categoryName = $category->name;
            return route('articles.index').$categoryID.$categoryName;
        })->implode(' | ');

        if ($categories == '' || $categories == null) return 'none';

        return $categories;
    }

    public function getTagsLinksAttribute()
    {
        $tags = $this->tags()->get()->map(function($tag)
        {
            $tagID = $tag->id;
            $tagName = $tag->name;
            return route('articles.index').$tagID.$tagName;
        })->implode(' | ');

        if ($tags == '' || $tags == null) return 'none';

        return $tags;
    }

    
    public function getArticlesByDetails()
    {
        $articles = $this->articles()->with(['categories', 'tags', 'author']);
        return $articles;
    }

}
