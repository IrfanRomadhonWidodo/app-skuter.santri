<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TambahKolomNominalKePembayaranSPP extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pembayaran_spp', [
            'nominal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'semester' // posisi kolom (optional)
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pembayaran_spp', 'nominal');
    }
}
