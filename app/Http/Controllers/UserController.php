<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }
    

    public function admins()
    {
        $admins = Admin::all();
        return view('super-admin.admins', compact('admins'));
    }

    public function admin_add()
    {
        return view('super-admin.admins-add');
    }

    // Menyimpan admin baru ke database
    public function admin_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'position' => 'nullable',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:8|confirmed',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'position' => $request->position,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign role ke admin
        $admin->assignRole('admin');

        return redirect()->route('admin.admins')->with('success', 'Admin created successfully');
    }

    // Menampilkan detail admin
    // public function admin_show($id)
    // {
    //     $admin = Admin::find($id);
    //     return view('super-admin.admins.show', compact('admin'));
    // }

    // Menampilkan form edit admin
    public function admin_edit($id)
    {
        $admin = Admin::find($id);
        return view('super-admin.admins-edit', compact('admin'));
    }

    // Update admin di database
    public function admin_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'position' => 'nullable',
            'email' => 'required|email|unique:admins,email,' . $request->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $admin = Admin::find($request->id);
        $admin->update([
            'name' => $request->name,
            'position' => $request->position,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $admin->password,
        ]);

        return redirect()->route('admin.admins')->with('success', 'Admin updated successfully');
    }

    // Menghapus admin dari database
    public function admin_delete($id)
    {
        Admin::destroy($id);
        return redirect()->route('admin.admins')->with('success', 'Admin deleted successfully');
    }

    public function viewActivityLogs()
    {
        $logs = ActivityLog::with('admin')->latest()->paginate(10);
        return view('super-admin.logactivity', compact('logs'));
    }


}
