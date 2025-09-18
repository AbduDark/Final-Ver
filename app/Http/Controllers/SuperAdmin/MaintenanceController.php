
<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $superAdmin = auth()->user();
        
        $maintenanceRequests = MaintenanceRequest::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->with(['store', 'technician'])->latest()->paginate(20);

        return view('superadmin.maintenance.index', compact('maintenanceRequests'));
    }
}
