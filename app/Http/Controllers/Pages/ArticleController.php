<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Post;

class ArticleController extends Controller
{
  public function index()
  {
    $articles = Post::where('status', TRUE)->orderBy('id', 'desc')->paginate(12);

    return view('article.index', [
      'pageTitle' => 'Tin Tức Mới',
    ], compact('articles'));
  }

  public function show($slug)
  {
    $article = Post::where('status', TRUE)->where('slug', $slug)->firstOrFail();

    return view('article.show', [
      'pageTitle' => $article->title,
    ], compact('article'));
  }
}
