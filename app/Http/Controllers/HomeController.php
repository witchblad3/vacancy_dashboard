<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $news = Article::query()
            ->where('id', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        foreach ($news as $article){
            if(strlen($article->article_text) > 400){
                $article->article_text = mb_substr($article->article_text, 0, 365, 'UTF-8') . '...';
            }
            $timestamp = strtotime($article->created_at);
            $published = date('d.m.Y H:i', $timestamp);
            $article->news_created = $published;
        }
        return view('news.index', [
            'news' => $news
        ]);
    }

    public function addNews(){
        $news = Article::query()
            ->where('author_id', '=', Auth::id())
            ->orderBy('created_at', 'desc');
        $newsCount = $news->count();
        $news = $news->paginate(8);
        foreach ($news as $article){
            $article->header = mb_substr($article->header, 0, 35, 'UTF-8') .'...';
            $article->article_text = mb_substr($article->article_text, 0, 91, 'UTF-8') . '...';
        }
        return view('news.add',[
            'news' => $news,
            'count' => $newsCount
        ]);
    }

    public function addNewsItem(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedFields = Validator::make($request->all(), [
            '*' => 'required',
            'file_news' => 'required|image'
        ]);
        if ($validatedFields->fails()) {
            return redirect()->back()->withErrors($validatedFields)
                ->withInput();
        }
        $header = $request->get('header_news');
        $text = $request->get('text_news');
        $filePath = $request->file('file_news')->store('uploads', 'public');
        Article::query()
        ->create([
            'author_id' => Auth::id(),
            'header' => $header,
            'article_text' => $text,
            'image_url' => $filePath
        ]);
        return redirect()->back()->with('success', 'Новость успешно добавлена');
    }

    public function getArticleJson($id): \Illuminate\Http\JsonResponse
    {
        $article = Article::query()
            ->find($id);

        return response()->json(['article' => $article]);
    }
}
