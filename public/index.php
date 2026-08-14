<?php

/**
 * Laravel - Application Entry Point.
 */

use Illuminate\Http\Request;

// Load the Composer autoload file and the application bootstrap.
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
