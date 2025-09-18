<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $superAdmin = auth()->user();

        $packages = Package::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->with('store')->latest()->paginate(20);

        return view('superadmin.packages.index', compact('packages'));
    }
}
