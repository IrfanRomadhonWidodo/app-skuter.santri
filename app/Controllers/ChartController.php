<?php

namespace App\Controllers;

use App\Models\PembayaranModel;

class ChartController extends BaseController
{
    public function index()
    {
        $model = new PembayaranModel();

        // Ambil data total pembayaran per bulan
        $data = $model->getTotalPembayaranPerBulan();

        return view('chart_view', ['chartData' => $data]);
    }
}
