<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class nafathAuthController extends Controller
{
    public function test(Request $request)
    {
        return "Welcome to nafath";
    }
}
