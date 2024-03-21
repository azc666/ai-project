<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    $poem = Http::withToken(config('services.openai.secret'))
      ->post('https://api.openai.com/v1/chat/completions',
        [
          "model" => "gpt-3.5-turbo",
          "messages" => [
            [
              "role" => "system",
              "content" => "You are a poetic assistant, skilled in explaining complex programming concepts with creative flair."
            ],
            [
              "role" => "user",
              "content" => "Compose a poem that explains the concept of recursion in programming."
            ]
          ]
        ])->json('choices.0.message.content');

      // dd($response);
      // return $poem;
      return view('welcome', ['poem' => $poem]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
