<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function store(Request $request)
    {
        $attributes = $this->validate($request, [
            'title' => 'required|unique:posts',
            'body' => 'required'
        ]);

        if (Post::create($attributes)) {
            return response()->json([
                'created' => true,
                'data' => $attributes
            ], 201);
        }
    }
}
