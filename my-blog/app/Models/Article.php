<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Article extends Model
{
    protected $guarded = ['created_at', 'updated_at'];

    protected $dates = [
        'published_at',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tags::class);
    }
    public function getArticlesByPaginate($params)
    {
        return $this->orderBy('created_at', 'DESC')->paginate($params);
    }
    public function attachTagsToArticles($params)
    {
        return $this->tags()->attach($params);
    }
    public function syncTagsToArticles($params)
    {
        return $this->tags()->sync($params);
    }

}
