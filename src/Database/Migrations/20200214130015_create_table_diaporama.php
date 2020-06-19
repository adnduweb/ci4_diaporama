<?php

namespace Adnduweb\Ci4_diaporama\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_create_table_diaporama extends Migration
{
    public function up()
    {
        /************************************************************
         *
         * Diaporama
         *
         ************************************************************/
        $fields = [
            'id'                        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
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
        $this->forge->addKey('id', true);
        $this->forge->createTable('diaporamas');


        $fields = [
            'id_diaporama_lang' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'diaporama_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'id_lang'           => ['type' => 'INT', 'constraint' => 11],
            'name'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'sous_name'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'description_short' => ['type' => 'TEXT'],
            'slug'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'url_bouton_diapo'  => ['type' => 'VARCHAR', 'constraint' => 255],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id_diaporama_lang', true);
        $this->forge->addKey('id_lang');
        $this->forge->addForeignKey('diaporama_id', 'diaporamas', 'id', false, 'CASCADE');
        $this->forge->createTable('diaporamas_langs', true);

        /************************************************************
         *
         * Slide
         *
         ************************************************************/
        $fields = [
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'diaporama_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'id_field'     => ['type' => 'BIGINT', 'constraint' => 16],
            'options'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'handle'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'color_bg'     => ['type' => 'VARCHAR', 'constraint' => 48, 'null' => true],
            'order'        => ['type' => 'INT', 'constraint' => 11],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'   => ['type' => 'DATETIME', 'null' => true],
        ];
        //$this->forge->addField(['id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true]]);
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('diaporama_id', 'diaporamas', 'id', false, 'CASCADE');
        $this->forge->createTable('diaporamas_slides');


        $fields = [
            'id_diaporama_slide_lang' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'slide_id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'id_lang'                 => ['type' => 'INT', 'constraint' => 11],
            'name_one'                => ['type' => 'VARCHAR', 'constraint' => 255],
            'name_two'                => ['type' => 'VARCHAR', 'constraint' => 255],
            'description_one'         => ['type' => 'TEXT'],
            'description_two'         => ['type' => 'TEXT'],
            'bouton'                  => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'                    => ['type' => 'VARCHAR', 'constraint' => 255],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id_diaporama_slide_lang', true);
        $this->forge->addKey('id_lang');
        $this->forge->addForeignKey('slide_id', 'diaporamas_slides', 'id', false, 'CASCADE');
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
