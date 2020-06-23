<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Resources\Post as PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(6);
        return PostResource::collection($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return PostResource
     */
    public function store(Request $request)
    {
        $post = $request->isMethod('put') ? Post::findorFail($request->id) : new Post;
        $post->id = $request->input('id');
        $post->title = $request->input('title');
        $post->body = $request->input('body');

        if ($post->save()) {
            return new PostResource($post);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Post $post
     * @return PostResource
     */
    public function show($id)
    {
        $post = Post::findorFail($id);
        return new PostResource($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Post $post
     * @return \Illuminate\Http\Response
     */
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Post $post
     * @return PostResource
     */
    public function destroy($id)
    {
        $post = Post::findorFail($id);
        if ($post->delete()) {
            return new PostResource($post);
        }
    }
}
