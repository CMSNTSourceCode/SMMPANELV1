<?php

namespace App\Http\Controllers\Api\Tools;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class UploadController extends Controller
{
  public function index(Request $request)
  {
    $request->validate([
      'file'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10000',
      'from'   => 'nullable|string',
      'upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10000',
    ]);

    $file = $request->file('file') ?? $request->file('upload');

    if (!$file) {
      return response()->json([
        'uploaded' => 0,
        'error'    => [
          'message' => 'File is not valid',
        ],
        'status'   => 400,
        'message'  => 'File is not valid',
      ], 400);
    }

    if (!$file->isValid()) {
      return response()->json([
        'status'   => 400,
        'error'    => [
          'message' => 'File is not valid',
        ],
        'uploaded' => 0,
        'message'  => 'File is not valid',
      ], 400);
    }

    $sendTo = $request->form ?? '';

    if ($sendTo === 'ckeditor') {
      $sendTo = 'article';
    } else {
      $sendTo = 'general';
    }

    $path = Helper::uploadFile($file, 'public', $sendTo);

    return response()->json([
      'data'     => [
        'path' => $path,
        'link' => asset($path),
      ],
      'url'      => asset($path),
      'error'    => [],
      'uploaded' => 1,
      'fileName' => $file->getClientOriginalName(),
      'status'   => 200,
      'message'  => 'Upload file success',
    ]);
  }
}
