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
        return $this->belongsTo('App\Models\Category');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }
    public function getArticlesByPaginate($params)
    {
        $query = $this->orderBy('created_at', 'DESC')->paginate($params);
        return $query;
    }

}
