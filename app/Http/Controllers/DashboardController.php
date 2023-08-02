<?php

namespace App\Http\Controllers;

use App\Models\DataEntry;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $newsim = DataEntry::where('new_sim', 'yes')->get()->count();
        $appinstall = DataEntry::where('app_install', 'yes')->get()->count();
        $toffeegift = DataEntry::where('toffee_gift', 'yes')->get()->count();
        $rechareamount = DataEntry::where('recharge_package', 'yes')->sum('recharge_amount');
        $voiceamount = DataEntry::where('voice', 'yes')->sum('voice_amount');

        return view('pages.index', compact('newsim', 'appinstall', 'toffeegift', 'rechareamount', 'voiceamount'));
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
