<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.index');
    }

    public function table()
    {
        return view('pages.table');
    }

    public function form()
    {
        return view('pages.form');
    }
}
