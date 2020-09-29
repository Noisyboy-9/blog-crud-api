<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Transformers\PostTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;


class PostsController extends Controller
{
    /**
     * @var \League\Fractal\Manager
     */
    private Manager $fractal;

    /**
     * PostsController constructor.
     */
    public function __construct()
    {
        $this->fractal = new Manager();
    }


    /**
     * get all posts
     *
     * @return array
     */
    public function index()
    {
        $posts = Post::all();

        $resource = new Collection($posts, new PostTransformer());


        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * store a post
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $attributes = $this->validateRequest($request);

        Post::create([
            'title' => $attributes['title'],
            'body' => $attributes['body'],
        ]);

        return response()->json([
            'created' => true,
            'post' => $attributes,
        ], 201);
    }

    /**
     * show a post by id
     *
     * @param $id
     *
     * @return array|null
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);

        $resource = new Item($post, new PostTransformer());

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * update a post by id
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $attributes = $this->validateRequest($request);

        $post = Post::findOrFail($id);

        $post->update([
            'title' => $attributes['title'],
            'body' => $attributes['body'],
        ]);

        return response()->json([
            'updated' => true,
        ], 200);
    }

    /**
     * validate the incoming request
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateRequest(Request $request): array
    {
        return $this->validate($request, [
            'title' => 'required|unique:posts',
            'body' => 'required',
        ]);

    }
}
