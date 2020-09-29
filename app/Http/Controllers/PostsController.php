<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Transformers\PostTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;


class PostsController extends Controller
{
    /**
     * @var \League\Fractal\Manager
     */
    private Manager $fractal;

    public function __construct()
    {
        $this->fractal = new Manager();
    }

    public function index()
    {
        $posts = Post::all();

        $resource = new Collection($posts, new PostTransformer());

        return $this->fractal->createData($resource)->toArray();
    }


    public function store(Request $request)
    {
        $attributes = $this->validate($request, [
            'title' => 'required|unique:posts',
            'body' => 'required',
        ]);

        Post::create([
            'title' => $attributes['title'],
            'body' => $attributes['body'],
        ]);

        return response()->json([
            'created' => true,
            'post' => $attributes,
        ], 201);
    }
}
