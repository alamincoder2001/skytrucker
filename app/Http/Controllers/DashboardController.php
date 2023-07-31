<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');
        $todayDataList = DB::select("SELECT de.* FROM data_entries de WHERE de.deleted_at IS NULL AND date(de.created_at) = '$today'");
        $monthDataList = DB::select("SELECT de.* FROM data_entries de WHERE de.deleted_at IS NULL AND MONTH(de.created_at) = MONTH(now())");
        $yearDataList = DB::select("SELECT de.* FROM data_entries de WHERE de.deleted_at IS NULL AND YEAR(de.created_at) = YEAR(now())");
        return view('pages.index', compact('todayDataList', 'monthDataList', 'yearDataList'));
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
