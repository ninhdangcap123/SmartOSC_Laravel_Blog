<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = ['created_at', 'deleted_at', 'updated_at'];

    public function getCategoryByPaginate($params)
    {
        return $this->orderBy('created_at', 'DESC')->paginate($params);
    }
    public function getCategoryByNum($params)
    {
        return $this->take($params)->get();
    }
}
