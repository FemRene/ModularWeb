<?php

use Illuminate\Support\Facades\Route;

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/post', fn() => view('EXAMPLE::post'))->name('post');
});
