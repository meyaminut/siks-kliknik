<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role != 'owner') {
            return redirect('/login')->with('error', 'Akses ditolak.');
        }

        return view('owner.dashboard');
    }
}