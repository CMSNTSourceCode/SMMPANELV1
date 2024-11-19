<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class PostController extends Controller
{
  public function index()
  {
    $posts = Post::all();

    return view('admin.posts.index', compact('posts'));
  }

  public function create()
  {
    return view('admin.posts.create');
  }

  public function store(Request $request)
  {
    $payload = $request->validate([
      'title'       => 'nullable|string|max:255',
      'status'      => 'required|boolean',
      'content'     => 'required|string',
      'meta_data'   => 'nullable|array',
      'thumbnail'   => 'nullable|file|mimes:jpeg,jpg,png,gif|max:10000',
      'description' => 'nullable|string',
    ]);

    if ($request->hasFile('thumbnail')) {
      $payload['thumbnail'] = Helper::uploadFile($request->file('thumbnail'), 'public');
    }

    if (empty($payload['title'])) {
      $payload['slug'] = 'post-' . rand(1000, 9999);
    } else {
      $payload['slug'] = Post::generateSlug($payload['title']);
    }


    $payload['meta_data'] = [
      'keywords' => $payload['meta_data']['keywords'] ?? NULL,
    ];

    if (empty($payload['title'])) {
      $payload['title'] = 'POST - ' . rand(1000, 9999);
    }

    $payload['content'] = Helper::htmlPurifier($payload['content']);

    Post::create(array_merge($payload, [
      'author_id'   => auth()->user()->id,
      'author_name' => auth()->user()->username,
    ]));

    Helper::addHistory('Thêm bài viết ' . $payload['title'], $payload);

    return redirect()->to(route('admin.posts'))->with('success', 'Thêm bài viết thành công');
  }

  public function show($id)
  {
    $post = Post::findOrFail($id);

    return view('admin.posts.show', compact('post'));
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'title'       => 'nullable|string|max:255',
      'status'      => 'required|boolean',
      'content'     => 'required|string',
      'meta_data'   => 'nullable|array',
      'thumbnail'   => 'nullable|file|mimes:jpeg,jpg,png,gif|max:10000',
      'description' => 'nullable|string',
    ]);

    $post = Post::findOrFail($request->id);

    if ($request->hasFile('thumbnail')) {
      $payload['thumbnail'] = Helper::uploadFile($request->file('thumbnail'), 'public');
    }

    if (!empty($payload['title'])) {
      $payload['slug'] = Post::generateSlug($payload['title']);
    }

    $payload['meta_data'] = [
      'keywords' => $payload['meta_data']['keywords'] ?? NULL,
    ];

    $payload['content'] = Helper::htmlPurifier($payload['content']);

    $post->update(array_merge($payload));

    Helper::addHistory('Cập nhật bài viết ' . $post->title);

    return redirect()->back()->with('success', 'Cập nhật bài viết thành công');
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|integer',
    ]);

    $post = Post::findOrFail($request->id);

    $post->delete();

    Helper::addHistory('Xoá bài viết ' . $post->title . ' thành công');

    return response()->json([
      'status'  => 200,
      'message' => 'Xoá bài viết thành công',
    ]);
  }
}
