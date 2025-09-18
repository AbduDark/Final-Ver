
<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\{MaintenanceRequest, User};
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $requests = MaintenanceRequest::where('store_id', $user->store_id)
            ->with('technician')
            ->latest()
            ->paginate(20);

        return view('cashier.maintenance.index', compact('requests'));
    }

    public function create()
    {
        $user = auth()->user();
        $technicians = User::where('store_id', $user->store_id)
            ->where('type', 'admin')
            ->get();

        return view('cashier.maintenance.create', compact('technicians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_name' => 'required|string',
            'device_type' => 'required|in:hardware,software',
            'problem_description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'technician_id' => 'nullable|exists:users,id',
        ]);

        $user = auth()->user();

        MaintenanceRequest::create([
            'ticket_number' => $this->generateTicketNumber(),
            'device_name' => $request->device_name,
            'device_type' => $request->device_type,
            'problem_description' => $request->problem_description,
            'priority' => $request->priority,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'technician_id' => $request->technician_id,
            'store_id' => $user->store_id,
            'status' => 'pending',
        ]);

        return redirect()->route('cashier.maintenance.index')
            ->with('success', 'تم إنشاء طلب الصيانة بنجاح');
    }

    private function generateTicketNumber()
    {
        $date = date('Ymd');
        $sequence = str_pad(MaintenanceRequest::count() + 1, 4, '0', STR_PAD_LEFT);
        return "MAINT-{$date}-{$sequence}";
    }
}
