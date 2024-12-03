<?php

namespace App\Exports;

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
        return OfflineOrder::where('admin_id', Auth::id())->select(
            'id',
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
            'Subtotal',
            'Tax',
            'Total',
            'Status',
            'Timestamp',
        ];
    }
}
