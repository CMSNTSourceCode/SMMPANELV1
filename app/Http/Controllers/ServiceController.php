<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
  public function index(Request $request)
  {
    $services   = Service::where('status', true)->get();
    $categories = Category::where('status', true)->orderBy('priority', 'desc')->get();

    return view('services.index', [
      'pageTitle' => __t('Tạo đơn hàng mới'),
    ], compact('services', 'categories'));
  }
}
