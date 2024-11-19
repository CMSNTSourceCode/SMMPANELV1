<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
  public function index($id)
  {
    $lang = Language::findOrFail($id);

    $translates = $lang->translates();

    $notTranslates = [];
    $allTranslates = [];

    foreach ($translates as $key => $value) {
      if (empty($value)) {
        $notTranslates[$key] = $value;
      }

      if ($key === $value) {
        $notTranslates[$key] = $value;
      } else {
        $allTranslates[$key] = $value;
      }
    }

    return view('admin.languages.translation', compact('lang', 'translates', 'notTranslates', 'allTranslates'));
  }

  public function update(Request $request)
  {
    $payload = $request->validate([
      'id'    => 'required|exists:languages,id',
      'index' => 'required|string',
      'value' => 'nullable|string',
    ]);

    $lang = Language::findOrFail($payload['id']);

    $translates = $lang->translates();

    $translates[$payload['index']] = $payload['value'];

    $path = resource_path("lang/{$lang->code}.json");

    file_put_contents($path, json_encode($translates, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    return response()->json([
      'status'  => 200,
      'message' => 'Cập nhật thành công',
    ]);
  }

  public function addKey(Request $request, $id)
  {
    $payload = $request->validate([
      'key'   => 'required|string|max:255',
      'value' => 'required|string',
    ]);

    $lang = Language::findOrFail($id);

    $translates = $lang->translates();

    if (isset($translates[$payload['key']])) {
      return response()->json([
        'status'  => 400,
        'message' => 'Key already exists',
      ], 400);
    }

    $translates[$payload['key']] = $payload['value'];

    $path = resource_path("lang/{$lang->code}.json");

    file_put_contents($path, json_encode($translates, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    return response()->json([
      'status'  => 200,
      'message' => 'Key added successfully',
    ]);
  }
}
