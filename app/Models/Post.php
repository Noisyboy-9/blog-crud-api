<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed id
 * @property mixed title
 * @property mixed body
 * @property mixed created_at
 * @property mixed updated_at
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body'];

    public function path()
    {
        return "/posts/{$this->id}";
    }
}
