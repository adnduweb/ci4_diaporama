<?php

namespace Adnduweb\Ci4_diaporama\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_diaporamas extends Migration
{
    public function up()
    {
        $fields = [
            'id_diaporama'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'template'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'active'             => ['type' => 'INT', 'constraint' => 11],
            'handle'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'order'              => ['type' => 'INT', 'constraint' => 11],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
            'updated_at'         => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'         => ['type' => 'DATETIME', 'null' => true],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id_diaporama', true);
        $this->forge->addKey('created_at');
        $this->forge->addKey('updated_at');
        $this->forge->addKey('deleted_at');
        $this->forge->createTable('diaporamas');


        $fields = [
            'id_diaporama'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'id_lang'           => ['type' => 'INT', 'constraint' => 11],
            'name'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'name_2'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'description_short' => ['type' => 'TEXT'],
            'description'       => ['type' => 'TEXT'],
            'meta_title'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'meta_description'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'tags'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'              => ['type' => 'VARCHAR', 'constraint' => 255],
        ];

        $this->forge->addField($fields);
        // $this->forge->addKey(['id_item', 'id_lang'], false, true);
        $this->forge->addKey('id_item');
        $this->forge->addKey('id_lang');
        $this->forge->addForeignKey('id_diaporama', 'diaporamas', 'id_diaporama', false, 'CASCADE');
        $this->forge->createTable('diaporamas_langs', true);

    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('diaporamas');
        $this->forge->dropTable('diaporamas_langs');
    }
}
