<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransaksiExport implements FromView, ShouldAutoSize
{
    protected $transaksis;

    protected $user;

    public function __construct($transaksis, $user)
    {
        $this->transaksis = $transaksis;
        $this->user = $user;
    }

    public function view(): View
    {
        return view('admin.export.transaksi_excel', [
            'transaksis' => $this->transaksis,
            'user' => $this->user,
        ]);
    }
}
