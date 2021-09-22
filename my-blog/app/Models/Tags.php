<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use HasFactory;
    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    public function articles(){
        return $this->belongsToMany(Article::class);
    }
    public function getTagsByPaginate($params)
    {
        return $this->orderBy('created_at', 'DESC')->paginate($params);
    }
}
