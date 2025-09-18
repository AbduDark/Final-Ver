<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $superAdmin = auth()->user();

        $notifications = Notification::whereHas('store', function($query) use ($superAdmin) {
                $query->where('super_admin_id', $superAdmin->id);
            })
            ->with('store')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Notification::whereHas('store', function($query) use ($superAdmin) {
                $query->where('super_admin_id', $superAdmin->id);
            })
            ->whereNull('read_at')
            ->count();

        return view('superadmin.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(Notification $notification)
    {
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function getUnread(Request $request)
    {
        $superAdmin = auth()->user();

        $notifications = Notification::whereHas('store', function($query) use ($superAdmin) {
                $query->where('super_admin_id', $superAdmin->id);
            })
            ->whereNull('read_at')
            ->with('store')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json($notifications);
    }
}
