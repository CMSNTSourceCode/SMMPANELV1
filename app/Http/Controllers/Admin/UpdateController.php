<?php

namespace App\Http\Controllers\Admin;

use App\Libraries\Update;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
  public function index(Request $request)
  {
    $enable = env('PRJ_AUTO_UPDATE', false);

    if (!$enable) {
      return response()->json([
        'status'  => 200,
        'message' => 'Tính năng này đã bị vô hiệu hóa.',
      ], 200);
    }

    // if local environment, disable update
    if (env('APP_ENV') === 'local') {
      return response()->json([
        'status'  => 200,
        'message' => 'Tính năng này đã bị vô hiệu hóa trên local.',
      ], 200);
    }

    $run = !!$request->input('run', false);

    $app = new Update();

    $latestVersion = $app::checkUpdate();

    if ($latestVersion === false) {
      return response()->json([
        'status'  => 200,
        'message' => 'Không có bản cập nhật mới ^^!',
      ], 200);
    }

    if ($run === false) {
      return response()->json([
        'status'  => 200,
        'message' => 'Đã tìm thấy phiên bản mới, hãy cập nhật.',
        'data'    => [
          'can_update'   => !!$latestVersion,
          'version_code' => $latestVersion,
        ],
      ], 200);
    }

    $download = $app::downloadUpdate();

    if ($download === false) {
      return response()->json([
        'status'  => 500,
        'message' => 'Không thể tải xuống bản cập nhật.',
      ], 500);
    }

    $extractUpdate = $app::extractUpdate($download);

    if ($extractUpdate === false) {
      return response()->json([
        'status'  => 500,
        'message' => 'Không thể giải nén bản cập nhật.',
      ], 500);
    }

    try {
      $runUpdate = $app::runUpdate();

      if ($runUpdate !== true) {
        return response()->json([
          'status'  => 500,
          'message' => 'Không thể cập nhật hệ thống.',
        ], 500);
      }

      return response()->json([
        'data'    => [
          'updated'      => $runUpdate,
          'version_code' => $latestVersion,
        ],
        'status'  => 200,
        'message' => 'Đã cập nhật lên phiên bản mới nhất là ' . $latestVersion . ' [' . ($runUpdate ? 'OK' : 'FAIL') . '].',
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status'  => 500,
        'message' => $e->getMessage() ?? 'Không thể cập nhật hệ thống.',
      ], 500);
    }
  }


}
