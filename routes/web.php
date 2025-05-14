<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'Laravel' => app()->version(),
        'Date' => date('d-m-Y H:i:s'),
    ];
});

require __DIR__.'/api.php';
