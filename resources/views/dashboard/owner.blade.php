@extends('layouts.app')
@section('title', 'Dashboard Owner')
@section('badge')
    <span class="text-xs px-2.5 py-1 rounded-full uppercase font-bold bg-purple-500/20 text-purple-400 border border-purple-500/30">Owner</span>
@endsection
@section('content')
<div class="bg-slate-800 border border-slate-700 p-8 rounded-2xl max-w-lg w-full text-center shadow-xl">
    <h2 class="text-2xl font-bold mb-4">Selamat datang di Dashboard Owner</h2>
    <div class="bg-slate-900 p-4 rounded-xl text-left space-y-2 text-sm border border-slate-700/50">
        <div class="flex justify-between"><span class="text-slate-400">Nama:</span> <span>{{ auth()->user()->name }}</span></div>
        <div class="flex justify-between"><span class="text-slate-400">Username:</span> <span>{{ auth()->user()->username }}</span></div>
    </div>
</div>
@endsection