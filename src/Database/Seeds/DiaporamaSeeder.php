<?php

namespace Adnduweb\Ci4_diaporama\Database\Seeds;

use Adnduweb\Ci4_diaporama\Models\DiaporamaModel;
use joshtronic\LoremIpsum;

class DiaporamaSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $lipsum = new LoremIpsum();
        // Define default project setting templates
        $rows = [
            [
                'id_diaporama'              => 1,
                'active'                    => 1,
                'handle'                    => 'diapo-1',
                'dimensions'                => '1920|700',
                'transparent_mask'          => 0,
                'transparent_mask_color_bg' => '#000000',
                'bouton_diapo'              => 0,
                'order'                     => 1,
                'created_at'                => date('Y-m-d H:i:s')
            ]

        ];
        $rowsLang = [
            [
                'id_diaporama'      => 1,
                'id_lang'           => 1,
                'name'              => 'Welcome to CodeIgniter',
                'sous_name'         => 'Bonjour',
                'description_short' => $lipsum->sentence(),
                'url_bouton_diapo'          => '',
            ]

        ];

        // Check for and create project setting templates
        //$diaporamas = new DiaporamaModel();
        $db = \Config\Database::connect();
        foreach ($rows as $row) {
            $page = $db->table('diaporamas')->where('id_diaporama', $row['id_diaporama'])->get()->getRow();
            //print_r($page); exit;
            if (empty($page)) {
                // No setting - add the row
                $db->table('diaporamas')->insert($row);
            }
        }

        foreach ($rowsLang as $rowLang) {
            $pagelang = $db->table('diaporamas_langs')->where('id_diaporama', $rowLang['id_diaporama'])->get()->getRow();

            if (empty($pagelang)) {
                // No setting - add the row
                $db->table('diaporamas_langs')->insert($rowLang);
            }
        }

        $rowsTabs = [
            [
                'id_parent'         => 17,
                'depth'             => 2,
                'left'              => 33,
                'right'             => 34,
                'position'          => 1,
                'section'           => 0,
                'module'            => 'Adnduweb\Ci4_diaporama',
                'class_name'        => 'AdminDiaporamas',
                'active'            =>  1,
                'icon'              => '',
                'slug'             => 'diaporamas',
                'name_controller'       => ''
            ],
        ];

        $rowsTabsLangs = [
            [
                'id_lang'         => 1,
                'name'             => 'diaporamas',
            ],
            [
                'id_lang'         => 2,
                'name'             => 'diaporamas',
            ],
        ];

        foreach ($rowsTabs as $row) {
            $tab = $db->table('tabs')->where('class_name', $row['class_name'])->get()->getRow();
            //print_r($tab); exit;
            if (empty($tab)) {
                // No setting - add the row
                $db->table('tabs')->insert($row);
                $newInsert = $db->insertID();
                $i = 0;
                foreach ($rowsTabsLangs as $rowLang) {
                    $rowLang['tab_id']   = $newInsert;
                    // No setting - add the row
                    $db->table('tabs_langs')->insert($rowLang);
                    $i++;
                }
            }
        }

        /**
         *
         * Gestion des permissions
         */
        $rowsPermissionsdiaporamas = [
            [
                'name'              => 'Diaporamas::views',
                'description'       => 'Voir les diaporamas',
                'is_natif'          => '0',
            ],
            [
                'name'              => 'Diaporamas::create',
                'description'       => 'Créer des diaporamas',
                'is_natif'          => '0',
            ],
            [
                'name'              => 'Diaporamas::edit',
                'description'       => 'Modifier les diaporamas',
                'is_natif'          => '0',
            ],
            [
                'name'              => 'Diaporamas::delete',
                'description'       => 'Supprimer des diaporamas',
                'is_natif'          => '0',
            ]
        ];

        // On insére le role par default au user
        foreach ($rowsPermissionsdiaporamas as $row) {
            $tabRow =  $db->table('auth_permissions')->where(['name' => $row['name']])->get()->getRow();
            if (empty($tabRow)) {
                // No langue - add the row
                $db->table('auth_permissions')->insert($row);
            }
        }

        //Gestion des module
        $rowsModulediaporamas = [
            'name'       => 'diaporamas',
            'namespace'  => 'Adnduweb\Ci4_diaporama',
            'active'     => 1,
            'version'    => '1.0.2',
            'created_at' =>  date('Y-m-d H:i:s')
        ];

        $tabRow =  $db->table('modules')->where(['name' => $rowsModulediaporamas['name']])->get()->getRow();
        if (empty($tabRow)) {
            // No langue - add the row
            $db->table('modules')->insert($rowsModulediaporamas);
        }
    }
}
