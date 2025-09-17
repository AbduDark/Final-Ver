
<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\{User, Store};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $superAdmin = auth()->user();
        $users = User::whereHas('store', function($query) use ($superAdmin) {
            $query->where('super_admin_id', $superAdmin->id);
        })->with('store')->paginate(15);
        
        return view('superadmin.users.index', compact('users'));
    }

    public function create()
    {
        $superAdmin = auth()->user();
        $stores = Store::where('super_admin_id', $superAdmin->id)->get();
        
        return view('superadmin.users.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'type' => 'required|in:admin,cashier',
            'store_id' => 'required|exists:stores,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'store_id' => $request->store_id,
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function edit(User $user)
    {
        $superAdmin = auth()->user();
        $stores = Store::where('super_admin_id', $superAdmin->id)->get();
        
        return view('superadmin.users.edit', compact('user', 'stores'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'type' => 'required|in:admin,cashier',
            'store_id' => 'required|exists:stores,id',
        ]);

        $data = $request->only(['name', 'email', 'type', 'store_id']);
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        $user->delete();
        
        return redirect()->route('superadmin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }
}
