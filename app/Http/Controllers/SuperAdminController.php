<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
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

    public function searchAdmin(Request $request)
    {
        // Ambil query dari form pencarian
        $query = $request->input('query');

        // Filter data berdasarkan query jika ada
        $admins = Admin::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhere('position', 'like', '%' . $query . '%');
            })
            ->paginate(10); // Pagination, Anda dapat menyesuaikan jumlahnya

        // Kembalikan ke view dengan data admins
        return view('super-admin.admins', compact('admins'));
    }

    public function searchAdminLog(Request $request)
{
    // Ambil query dari form pencarian
    $query = $request->input('query');

    // Filter data berdasarkan query jika ada
    $logs = ActivityLog::query()
        ->with('admin') // Pastikan relasi 'admin' ada di model ActivityLog
        ->when($query, function ($q) use ($query) {
            $q->whereHas('admin', function ($subQuery) use ($query) {
                $subQuery->where('name', 'like', '%' . $query . '%'); // Cari berdasarkan nama admin
            })
            ->orWhere('activity', 'like', '%' . $query . '%') // Cari berdasarkan aktivitas
            ->orWhereRaw("DATE_FORMAT(created_at, '%d %b %Y %H:%i') LIKE ?", ['%' . $query . '%']); // Pencarian dengan format tanggal dan waktu
        })
        ->when($query, function ($q) use ($query) {
            // Membagi query berdasarkan spasi untuk mencari nama admin dan tanggal
            $terms = explode(' ', $query);
            foreach ($terms as $term) {
                $q->where(function ($subQuery) use ($term) {
                    $subQuery->whereHas('admin', function ($adminQuery) use ($term) {
                        $adminQuery->where('name', 'like', '%' . $term . '%');
                    })
                    ->orWhereRaw("DATE_FORMAT(created_at, '%d %b %Y %H:%i') LIKE ?", ['%' . $term . '%']);
                });
            }
        })
        ->paginate(20); // Pagination

    // Kembalikan ke view dengan data logs
    return view('super-admin.logactivity', compact('logs'));
}

}
