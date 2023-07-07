<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/posts/{post:slug}', [PostController::class, 'show']);

Route::get('/categories/{category:slug}', fn (Category $category) =>
    view('posts', [
        'posts' => $category->posts,
        'categories' => Category::all(),
        'currentCategory' => $category
    ])
)->name('category');

Route::get('/posts/authors/{author:username}', function (User $author) {
    return view('posts', [
        'posts' => $author->posts
    ]);
});