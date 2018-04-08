<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;

class UsersController extends Controller{

    public function __construct()
    {
        $this -> middleware('auth',['except' => ['show']]);
    }

}