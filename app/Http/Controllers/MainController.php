<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;



class MainController extends Controller
{
    public function __invoke(Request $request) {
        return view('main');
    }
}
