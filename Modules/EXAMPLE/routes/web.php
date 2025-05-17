<?php

use Illuminate\Support\Facades\Route;

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', fn() => view('EXAMPLE::index'))->name('index');
});
