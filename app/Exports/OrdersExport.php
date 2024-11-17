<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    /**
     * Get data for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Order::select(
            'id',
            'user_id',
            'subtotal',
            'discount',
            'tax',
            'total',
            'name',
            'phone',
            'address',
            'city',
            'province',
            'zip_code',
            'type',
            'status',
            'is_shipping_different',
            'delivered_date',
            'canceled_date'
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
            'User ID',
            'Subtotal',
            'Discount',
            'Tax',
            'Total',
            'Name',
            'Phone',
            'Address',
            'City',
            'Province',
            'Zip Code',
            'Type',
            'Status',
            'Is Shipping Different',
            'Delivered Date',
            'Canceled Date',
        ];
    }
}
