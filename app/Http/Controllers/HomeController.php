<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;

class HomeController extends Controller
{
    public function index()
    {
        $destacadas = Pelicula::inRandomOrder()->limit(6)->get();
        return view('welcome', compact('destacadas'));
    }
}