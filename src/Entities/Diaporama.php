<?php

namespace Adnduweb\Ci4_diaporama\Entities;

use CodeIgniter\Entity;

class Diaporama extends Entity
{
    use \Tatter\Relations\Traits\EntityTrait;
    protected $table          = 'diaporamas';
    protected $tableLang      = 'diaporamas_langs';
    protected $tableSlide     = 'diaporamas_slides';
    protected $tableSlideLang = 'diaporamas_slides_langs';
    protected $primaryKey     = 'id_diaporama';

    protected $datamap = [];
    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [];

    public function getId()
    {
        return $this->id_diaporama ?? null;
    }
    public function getName()
    {
        return $this->attributes['name'] ?? null;
    }

    public function getNameLang(int $id_lang)
    {
        foreach ($this->diaporamas_langs as $lang) {
            if ($id_lang == $lang->id_lang) {
                return $lang->name ?? null;
            }
        }
    }


    public function _prepareLang()
    {
        $lang = [];
        if (!empty($this->id_diaporama)) {
            foreach ($this->diaporamas_langs as $tabs_lang) {
                $lang[$tabs_lang->id_lang] = $tabs_lang;
            }
        }
        return $lang;
    }

    public function saveLang(array $data, int $key)
    {
        //print_r($data);
        $db      = \Config\Database::connect();
        $builder = $db->table($this->tableLang);
        foreach ($data as $k => $v) {
            $this->tableLang =  $builder->where(['id_lang' => $k, 'id_diaporama' => $key])->get()->getRow();
            // print_r($this->tableLang);
            if (empty($this->tableLang)) {
                $data = [
                    'id_diaporama'           => $key,
                    'id_lang'           => $k,
                    'name'              => $v['name'],
                    'description_short' => $v['description_short'],
                ];
                // Create the new participant
                $builder->insert($data);
            } else {
                $data = [
                    'id_diaporama'           => $this->tableLang->id_diaporama,
                    'id_lang'           => $this->tableLang->id_lang,
                    'name'              => $v['name'],
                    'description_short' => $v['description_short'],
                ];
                //print_r($data);
                $builder->set($data);
                $builder->where(['id_diaporama' => $this->tableLang->id_diaporama, 'id_lang' => $this->tableLang->id_lang]);
                $builder->update();
            }
        }
    }

    public function saveSlide($slides, object $diaporama)
    {


        if (!empty($slides)) {
            // echo '<pre>';
            // print_r($slides);
            // echo '</pre>';
            // echo
            //     exit;
            $db           = \Config\Database::connect();
            $builderSlide = $db->table($this->tableSlide);
            //On enregistre le slide
            $i = 1;
            foreach ($slides as $slide => $v) {
                $slideOnly =  $builderSlide->where(['id_field' => $slide, 'id_diaporama' => $diaporama->id_diaporama])->get()->getRow();

                if (empty($slideOnly)) {
                    $dataSlide = [
                        'id_diaporama' => $diaporama->id_diaporama,
                        'id_field'     => $slide,
                        'options'      => $v['options'],
                        'handle'       => 'slide' . $slide,
                        'color_bg'     => $v['color_bg'],
                        'order'        => $i,
                        'created_at'   => date('y-m-d H:i:s')
                    ];
                    // print_r($dataSlide);
                    $builderSlide->insert($dataSlide);
                    $id_slide = $db->insertID();
                    if (!empty($id_slide)) {
                        $builderSlidelang = $db->table($this->tableSlideLang);
                        foreach ($v['lang'] as $k => $s) {
                            $slideLangOnly =  $builderSlidelang->where(['id_slide' => $id_slide, 'id_lang' => $k])->get()->getRow();
                            if (empty($slideLangOnly)) {
                                $datalang = [
                                    'id_slide'        => $id_slide,
                                    'id_lang'         => $k,
                                    'name_one'        => $s['name_one'],
                                    'name_two'        => $s['name_two'],
                                    'description_one' => $s['description_one'],
                                    'description_two' => $s['description_two'],
                                    'bouton'          => $s['bouton'],
                                    'slug'            => $s['slug'],
                                ];
                                $builderSlidelang->insert($datalang);
                            } else {
                                $datalang = [
                                    'id_slide'        => $id_slide,
                                    'id_lang'         => $k,
                                    'name_one'        => $s['name_one'],
                                    'name_two'        => $s['name_two'],
                                    'description_one' => $s['description_one'],
                                    'description_two' => $s['description_two'],
                                    'bouton'          => $s['bouton'],
                                    'slug'            => $s['slug'],
                                ];
                                $builderSlidelang->set($datalang);
                                $builderSlidelang->where(['id_slide' => $id_slide, 'id_lang' => $k]);
                                $builderSlidelang->update();
                            }
                        }
                    }
                } else {
                    $dataSlide = [
                        'id_diaporama' => $diaporama->id_diaporama,
                        'id_field'     => $slide,
                        'options'      => $v['options'],
                        'handle'       => 'slide' . $slide,
                        'color_bg'     => $v['color_bg'],
                        'order'        => $i,
                        'updated_at'   => date('y-m-d H:i:s')
                    ];
                    $builderSlide->set($dataSlide);
                    $builderSlide->where(['id_diaporama' => $diaporama->id_diaporama, 'id_field' => $slide]);
                    $builderSlide->update();

                    $builderSlidelang = $db->table($this->tableSlideLang);
                    foreach ($v['lang'] as $k => $s) {
                        $slideLangOnly =  $builderSlidelang->where(['id_slide' => $slideOnly->id_slide, 'id_lang' => $k])->get()->getRow();
                        // echo '<pre>';
                        // print_r($slideLangOnly);
                        // echo '</pre>';
                        if (empty($slideLangOnly)) {
                            $datalang = [
                                'id_slide'        => $slideOnly->id_slide,
                                'id_lang'         => $k,
                                'name_one'        => $s['name_one'],
                                'name_two'        => $s['name_two'],
                                'description_one' => $s['description_one'],
                                'description_two' => $s['description_two'],
                                'bouton'          => $s['bouton'],
                                'slug'            => $s['slug'],
                            ];
                            
                            $builderSlidelang->insert($datalang);
                        } else {
                            $datalang = [
                                'id_slide'        => $slideOnly->id_slide,
                                'id_lang'         => $k,
                                'name_one'        => $s['name_one'],
                                'name_two'        => $s['name_two'],
                                'description_one' => $s['description_one'],
                                'description_two' => $s['description_two'],
                                'bouton'          => $s['bouton'],
                                'slug'            => $s['slug'],
                            ];

                           
                            $builderSlidelang->set($datalang);
                            $builderSlidelang->where(['id_slide' => $slideOnly->id_slide, 'id_lang' => $k]);
                            $builderSlidelang->update();
                        }
                    }
                }

                $i++;
            }
        }
        //exit;
    }
}
