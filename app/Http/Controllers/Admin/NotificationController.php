<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function markAsReadNotification(){
        DB::table('notifications')->where('read_at', null)->update(['read_at'=> Carbon::now()]);
	}

    public function allNotifications()
	{
		$all_notification = DB::table('notifications')->where('deleted_at', null)->get();
		return view('admin.pages.notification.all_notifications', compact('all_notification'));
	}

    public function clearAll()
	{
        DB::table('notifications')->where('deleted_at', null)->update(['deleted_at'=> Carbon::now()]);
		return redirect()->back();
	}
}
