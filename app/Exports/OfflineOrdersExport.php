<?php

namespace App\Exports;

use App\Models\Admin;
use App\Models\OfflineOrder;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OfflineOrdersExport implements FromCollection, WithHeadings
{
    /**
     * Get data for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Cek apakah user memiliki role 'admin'
        if ($user && $user->hasRole('admin')) {
            return OfflineOrder::where('admin_id', $user->id)
                ->select(
                    'id',
                    'admin_id',
                    'subtotal',
                    'tax',
                    'total',
                    'transaction',
                    'status',
                    'created_at'
                )->get();
        }

        // Jika bukan admin, ambil semua data OfflineOrder
        return OfflineOrder::select(
            'id',
            'admin_id',
            'subtotal',
            'tax',
            'total',
            'transaction',
            'status',
            'created_at'
        )->get();
    }

    /**
     * Add headers to the spreadsheet.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Admin ID',
            'Subtotal',
            'Tax',
            'Total',
            'Status',
            'Timestamp',
        ];
    }
}
