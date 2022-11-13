<?php

namespace App\Http\Controllers;

use App\Models\Trader;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        $traders = Trader::all();

        return view('home.index', [
            'traders' => $traders
        ]);
    }
}
