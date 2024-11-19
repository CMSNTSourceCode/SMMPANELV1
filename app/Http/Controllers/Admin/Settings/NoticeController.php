<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        foreach (Notification::get() as $item) {
            $notices[strtolower($item->name)] = $item->value;
        }

        return view('admin.settings.notices', $notices);
    }

    public function update(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'content' => 'nullable|string',
            'youtube_id' => 'nullable|string',
        ]);

        $type = $request->input('type', null);
        $ytbId = $request->input('youtube_id', null);
        $value = $request->input('content', null);


        if ($ytbId) {
            $config = Notification::firstOrCreate(['name' => 'ytb_' . $type], ['value' => '']);

            if ($config)
                $config->update([
                    'value' => Helper::htmlPurifier($ytbId),
                ]);
        }

        $config = Notification::firstOrCreate(['name' => $type], ['value' => '']);

        if ($config)
            $config->update([
                'value' => Helper::htmlPurifier($value),
            ]);
        else
            return back()->with('error', 'Cập nhật thông báo thất bại [' . $type . '].');

        return redirect()->back()->with('success', 'Cập nhật thông báo thành công [' . $type . '].');
    }
}
