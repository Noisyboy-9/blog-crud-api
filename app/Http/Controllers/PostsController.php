<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'title' => 'required|unique:posts',
            'body' => 'required'
        ]);

        Post::create($attributes);
    }
}
