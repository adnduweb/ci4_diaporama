<?php

namespace Adnduweb\Ci4_diaporama\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_diaporamas extends Migration
{
    public function up()
    {
        /************************************************************
         *
         * Diaporama
         *
         ************************************************************/
        $fields = [
            'id_diaporama'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'active'                    => ['type' => 'INT', 'constraint' => 11],
            'handle'                    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'dimensions'                => ['type' => 'VARCHAR', 'constraint' => 48, 'null' => true],
            'transparent_mask'          => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'transparent_mask_color_bg' => ['type' => 'VARCHAR', 'constraint' => 48, 'null' => true],
            'bouton_diapo'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'order'                     => ['type' => 'INT', 'constraint' => 11],
            'created_at'                => ['type' => 'DATETIME', 'null' => true],
            'updated_at'                => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'                => ['type' => 'DATETIME', 'null' => true],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id_diaporama', true);
        $this->forge->addKey('created_at');
        $this->forge->addKey('updated_at');
        $this->forge->addKey('deleted_at');
        $this->forge->createTable('diaporamas');


        $fields = [
            'id_diaporama'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'id_lang'           => ['type' => 'INT', 'constraint' => 11],
            'name'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'sous_name'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'description_short' => ['type' => 'TEXT'],
            'url_bouton_diapo'  => ['type' => 'VARCHAR', 'constraint' => 255],
        ];

        $this->forge->addField($fields);
        // $this->forge->addKey(['id_item', 'id_lang'], false, true);
        $this->forge->addKey('id_item');
        $this->forge->addKey('id_lang');
        $this->forge->addForeignKey('id_diaporama', 'diaporamas', 'id_diaporama', false, 'CASCADE');
        $this->forge->createTable('diaporamas_langs', true);

        /************************************************************
         *
         * Slide
         *
         ************************************************************/

        $fields = [
            'id_slide'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_diaporama' => ['type' => 'INT', 'constraint' => 11],
            'id_field'     => ['type' => 'BIGINT', 'constraint' => 16],
            'options'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'handle'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'color_bg'     => ['type' => 'VARCHAR', 'constraint' => 48, 'null' => true],
            'order'        => ['type' => 'INT', 'constraint' => 11],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'   => ['type' => 'DATETIME', 'null' => true],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id_slide', true);
        $this->forge->addKey('created_at');
        $this->forge->addKey('updated_at');
        $this->forge->addKey('deleted_at');
        $this->forge->createTable('diaporamas_slides');


        $fields = [
            'id_slide'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'id_lang'         => ['type' => 'INT', 'constraint' => 11],
            'name_one'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'name_two'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'description_one' => ['type' => 'TEXT'],
            'description_two' => ['type' => 'TEXT'],
            'bouton'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'            => ['type' => 'VARCHAR', 'constraint' => 255],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id_slide');
        $this->forge->addKey('id_lang');
        $this->forge->addForeignKey('id_slide', 'diaporamas_slides', 'id_slide', false, 'CASCADE');
        $this->forge->createTable('diaporamas_slides_langs', true);
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('diaporamas');
        $this->forge->dropTable('diaporamas_langs');
        $this->forge->dropTable('diaporamas_slides');
        $this->forge->dropTable('diaporamas_slides_langs');
    }
}
