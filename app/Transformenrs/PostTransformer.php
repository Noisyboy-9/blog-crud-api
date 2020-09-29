<?php

namespace App\Transformers;

use App\Models\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    public function transform(Post $post)
    {
        return [
            'id' => (int) $post->id,
            'title' => $post->title,
            'body' => $post->body,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
        ];
    }
}
