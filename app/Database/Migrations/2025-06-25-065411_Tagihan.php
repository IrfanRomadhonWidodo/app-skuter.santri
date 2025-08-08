<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tagihan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nim'               => ['type' => 'VARCHAR', 'contraint' => 100],
            'tahun_ajaran'      => ['type' => 'VARCHAR', 'contraint' => 100],
            'semester'          => ['type' => 'VARCHAR', 'contraint' => 100],
            'status'            => ['type' => 'VARCHAR', 'contraint' => 100]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('Tagihan');
    }
    public function down()
    {
        $this->forge->dropTable('Tagihan');
    }
}
