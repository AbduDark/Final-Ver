
<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Product;
use App\Models\MaintenanceRequest;
use App\Models\Invoice;
use Carbon\Carbon;

class NotificationService
{
    public static function createLowStockNotification($product)
    {
        return Notification::create([
            'store_id' => $product->store_id,
            'user_id' => null, // للجميع في المتجر
            'type' => 'low_stock',
            'title' => 'تنبيه مخزون منخفض',
            'message' => "المنتج {$product->name} وصل للحد الأدنى من المخزون ({$product->quantity})",
            'data' => [
                'product_id' => $product->id,
                'current_quantity' => $product->quantity,
                'min_quantity' => $product->min_quantity
            ]
        ]);
    }

    public static function createMaintenanceNotification($maintenance)
    {
        return Notification::create([
            'store_id' => $maintenance->store_id,
            'user_id' => null,
            'type' => 'maintenance_request',
            'title' => 'طلب صيانة جديد',
            'message' => "تم إنشاء طلب صيانة جديد برقم {$maintenance->ticket_number}",
            'data' => [
                'maintenance_id' => $maintenance->id,
                'ticket_number' => $maintenance->ticket_number
            ]
        ]);
    }

    public static function createDailyClosingReminder($storeId)
    {
        return Notification::create([
            'store_id' => $storeId,
            'user_id' => null,
            'type' => 'daily_closing',
            'title' => 'تذكير إقفال يومي',
            'message' => 'لا تنس إقفال الخزينة لليوم',
            'data' => [
                'date' => Carbon::today()->toDateString()
            ]
        ]);
    }

    public static function createHighSalesNotification($storeId, $amount)
    {
        return Notification::create([
            'store_id' => $storeId,
            'user_id' => null,
            'type' => 'high_sales',
            'title' => 'مبيعات ممتازة!',
            'message' => "تم تحقيق مبيعات عالية اليوم: " . number_format($amount, 2) . " ج.م",
            'data' => [
                'amount' => $amount,
                'date' => Carbon::today()->toDateString()
            ]
        ]);
    }

    public static function getUnreadNotifications($storeId, $userId = null)
    {
        $query = Notification::where('store_id', $storeId)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc');

        if ($userId) {
            $query->where(function($q) use ($userId) {
                $q->where('user_id', $userId)->orWhereNull('user_id');
            });
        }

        return $query->get();
    }

    public static function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }
}
