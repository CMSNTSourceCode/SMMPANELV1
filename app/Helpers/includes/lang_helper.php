<?php

use App\Helpers\Helper;
use App\Models\Language;
use Illuminate\Support\Facades\Cache;

if (!function_exists('currentLang')) {
  function currentLang($json = false): string|array
  {
    $lang = setting('primary_lang', 'vn');

    if (!$lang) {
      $lang = getCountry(request()->ip());
    }

    if (session()->has('locale')) {
      $lang = session()->get('locale');
    }

    if (auth()->check()) {
      $user = auth()->user();
      $lang = $user->language ?? $lang;
    }

    if ($json) {
      $data = Language::where('code', $lang)->first();

      if ($data !== null) {
        return $data;
      } else {
        return '[]';
      }
    }

    return $lang;
  }
}

if (!function_exists('getLangs')) {
  function getLangs()
  {
    if (Cache::has('languages')) {
      return Cache::get('languages');
    }

    $langues = Language::where('status', true)->get();

    if ($langues->count() === 0) {
      $defaultData = [
        'code'    => 'vn',
        'name'    => 'Vietnamese',
        'flag'    => 'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.3/flags/4x3/vn.svg',
        'status'  => true,
        'default' => true,
      ];
      // if not found any language, create default language
      $langues = Language::create($defaultData);

      $langues = collect([$langues]);
    }

    if ($langues->count() > 0) {
      Cache::put('languages', $langues, 30);
    }

    return $langues;
  }
}

if (!function_exists('getLangJson')) {
  function getLangJson($lang = null)
  {

    if ($lang === null) {
      $lang = currentLang();
    }

    $path = resource_path('lang/' . $lang . '.json');

    if (!file_exists($path)) {
      file_put_contents($path, json_encode([], JSON_UNESCAPED_UNICODE));
    }

    return json_decode(file_get_contents($path), true);
  }
}

if (!function_exists('currentVersion')) {
  function currentVersion()
  {
    if (Cache::has('current_version')) {
      return Cache::get('current_version');
    }

    $version = Helper::getConfig('version_code', 1000);

    Cache::put('current_version', $version, 120);

    return $version;
  }
}

if (!function_exists('__t')) {
  function __t($str, $params = [], $default = null)
  {
    $lang = currentLang();
    $path = resource_path('lang/' . $lang . '.json');

    if (!file_exists($path)) {
      file_put_contents($path, json_encode([], JSON_UNESCAPED_UNICODE));
    }
    $langFile = json_decode(file_get_contents($path), true);

    if (!isset($langFile[$str])) {
      $langFile[$str] = $str;
      file_put_contents($path, json_encode($langFile, JSON_UNESCAPED_UNICODE));
    }


    $string = $langFile[$str];

    if (count($params) > 0) {
      foreach ($params as $key => $value) {
        $string = str_replace(':' . $key . '', $value, $string);
      }
    }

    return $string ?? $default ?? $str;
  }
}



if (!function_exists('lang')) {
  function lang($str)
  {
    return __t($str);
  }
}
