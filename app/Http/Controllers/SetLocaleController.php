<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class SetLocaleController extends Controller
{
  /**
   * Handle the incoming request.
   *
   * @return RedirectResponse
   */
  public function __invoke($locale)
  {
    $availableLocales = [];

    $languages = Language::where('status', true)->get();

    foreach ($languages as $language) {
      $availableLocales[$language->code] = $language->name;
    }

    if (array_key_exists($locale, $availableLocales)) {
      session()->put('locale', $locale);

      if (auth()->check()) {
        User::find(auth()->user()->id)?->update(['language' => $locale]);
      }
    } else {
      session()->put('locale', setting('primary_lang', 'vn'));
    }

    return redirect()->back();
  }
}
