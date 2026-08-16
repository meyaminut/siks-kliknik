<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApotekerController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role != 'apoteker') {
            return redirect('/login')->with('error', 'Akses ditolak.');
        }

        return view('apoteker.dashboard');
    }
}