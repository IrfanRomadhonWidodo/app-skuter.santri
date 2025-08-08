<?php

namespace App\Controllers;

use App\Models\ModelPembayaran;
use App\Controllers\BaseController;
use App\Models\ModelPembayaranSPP;

class PembayaranSPP extends BaseController
{
    protected $pembayaranModel;

    public function __construct()
    {
        $this->pembayaranModel = new ModelPembayaranSPP();
    }

    public function spp()
    {
        // Data total status
        $totalLunas = $this->pembayaranModel->where('status', 'Lunas')->countAllResults();
        $totalCicilan = $this->pembayaranModel->where('status', 'Cicilan')->countAllResults();
        $totalBelum = $this->pembayaranModel->where('status', 'Belum Lunas')->countAllResults();

        // Grafik per bulan untuk masing-masing status
        $bulan = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $grafikLabel = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $grafikLunas = [];
        $grafikCicilan = [];
        $grafikBelum = [];

        foreach ($bulan as $b) {
            $grafikLunas[] = $this->pembayaranModel
                ->where('status', 'Lunas')
                ->where('MONTH(tanggal_bayar)', $b)
                ->countAllResults();

            $grafikCicilan[] = $this->pembayaranModel
                ->where('status', 'Cicilan')
                ->where('MONTH(tanggal_bayar)', $b)
                ->countAllResults();

            $grafikBelum[] = $this->pembayaranModel
                ->where('status', 'Belum Lunas')
                ->where('MONTH(tanggal_bayar)', $b)
                ->countAllResults();
        }

        // Progress per fakultas
        $fakultas = ['FAI', 'FSH', 'FST'];
        $fakultasProgress = [];

        foreach ($fakultas as $fk) {
            $totalFakultas = $this->pembayaranModel->where('fakultas', $fk)->countAllResults();
            $lunasFakultas = $this->pembayaranModel->where(['fakultas' => $fk, 'status' => 'Lunas'])->countAllResults();

            $persen = $totalFakultas > 0 ? round(($lunasFakultas / $totalFakultas) * 100, 1) : 0;

            $fakultasProgress[] = [
                'nama' => $fk,
                'persen' => $persen
            ];
        }

        // 5 Mahasiswa terakhir bayar
        $terakhirBayar = $this->pembayaranModel
            ->orderBy('tanggal_bayar', 'DESC')
            ->limit(5)
            ->find();

        return view('pembayaran-spp', [
            'totalLunas' => $totalLunas,
            'totalCicilan' => $totalCicilan,
            'totalBelum' => $totalBelum,
            'grafikLabel' => $grafikLabel,
            'grafikLunas' => $grafikLunas,
            'grafikCicilan' => $grafikCicilan,
            'grafikBelum' => $grafikBelum,
            'fakultasProgress' => $fakultasProgress,
            'terakhirBayar' => $terakhirBayar
        ]);
    }
    public function detailLunas()
    {
        $data = [
            'title' => 'Detail Pembayaran Lunas',
            'list' => $this->pembayaranModel
                ->where('status', 'Lunas')
                ->orderBy('tanggal_bayar', 'DESC')
                ->findAll()
        ];
        return view('detail-status', $data);
    }

    public function detailCicilan()
    {
        $data = [
            'title' => 'Detail Pembayaran Cicilan',
            'list' => $this->pembayaranModel
                ->where('status', 'Cicilan')
                ->orderBy('tanggal_bayar', 'DESC')
                ->findAll()
        ];
        return view('detail-status', $data);
    }

    public function detailBelum()
    {
        $data = [
            'title' => 'Detail Pembayaran Belum Lunas',
            'list' => $this->pembayaranModel
                ->where('status', 'Belum Lunas')
                ->orderBy('tanggal_bayar', 'DESC')
                ->findAll()
        ];
        return view('detail-status', $data);
    }
}
