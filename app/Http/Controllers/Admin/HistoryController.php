<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class HistoryController extends Controller
{
    public function index()
    {
        return view('admin.histories.index');
    }
}
