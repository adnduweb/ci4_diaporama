<?php

namespace Adnduweb\Ci4_diaporama\Entities;

use CodeIgniter\Entity;

class Diaporama extends Entity
{
    use \Tatter\Relations\Traits\EntityTrait;
    use \App\Traits\BuilderEntityTrait;
    protected $table               = 'diaporamas';
    protected $tableLang           = 'diaporamas_langs';
    protected $tableSlide          = 'diaporamas_slides';
    protected $tableSlideLang      = 'diaporamas_slides_langs';
    protected $primaryKey          = 'id';
    protected $primaryKeyLang      = 'diaporama_id';
    protected $primaryKeySlide     = 'id';
    protected $primaryKeySlideLang = 'slide_id';

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

    public function getIdDiaporama()
    {
        return $this->id ?? null;
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


    public function saveLang(array $data, int $key)
    {
        //print_r($data);
        $db      = \Config\Database::connect();
        $builder = $db->table($this->tableLang);
        foreach ($data as $k => $v) {
            $this->tableLang =  $builder->where(['id_lang' => $k, $this->primaryKeyLang => $key])->get()->getRow();
            // print_r($this->tableLang);
            if (empty($this->tableLang)) {
                $data = [
                    $this->primaryKeyLang      => $key,
                    'id_lang'           => $k,
                    'name'              => $v['name'],
                    'sous_name'         => $v['sous_name'],
                    'description_short' => $v['description_short'],
                    'url_bouton_diapo'  => $v['url_bouton_diapo'],
                ];
                // Create the new participant
                $builder->insert($data);
            } else {
                $data = [
                    $this->primaryKeyLang      => $this->tableLang->{$this->primaryKeyLang},
                    'id_lang'           => $this->tableLang->id_lang,
                    'name'              => $v['name'],
                    'sous_name'         => $v['sous_name'],
                    'description_short' => $v['description_short'],
                    'url_bouton_diapo'  => $v['url_bouton_diapo'],
                ];
                //print_r($data);
                $builder->set($data);
                $builder->where([$this->primaryKeyLang => $this->tableLang->{$this->primaryKeyLang}, 'id_lang' => $this->tableLang->id_lang]);
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
                $slideOnly =  $builderSlide->where(['id_field' => $slide, $this->primaryKeyLang => $diaporama->{$this->primaryKey}])->get()->getRow();

                /** On convertit les images pour le front */
                $options = json_decode($v['options']);
                if (!strpos($options->media->format, 'custom') === false) {
                    $options->media->filename = site_url() . 'uploads' . $options->media->format;
                    $options->media->format = 'custom';
                    $pathinfo     = pathinfo($options->media->filename);
                    $options->media->basename = $pathinfo['basename'];
                    list($width, $height, $type, $attr) =  getimagesize($options->media->filename);
                    $options->media->dimensions = ['width' => $width, 'height' => $height];
                } else {

                    $options->media->format = 'custom';
                    $pathinfo     = pathinfo($options->media->filename);
                    $options->media->basename = $pathinfo['basename'];
                    list($width, $height, $type, $attr) =  getimagesize($options->media->filename);
                    $options->media->dimensions = ['width' => $width, 'height' => $height];
                }
                $v['options'] = json_encode($options);


                if (empty($slideOnly)) {
                    $dataSlide = [
                        $this->primaryKeyLang => $diaporama->{$this->primaryKey},
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
                            $slideLangOnly =  $builderSlidelang->where([$this->primaryKeySlideLang => $id_slide, 'id_lang' => $k])->get()->getRow();
                            if (empty($slideLangOnly)) {
                                $datalang = [
                                    $this->primaryKeySlideLang        => $id_slide,
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
                                    $this->primaryKeySlideLang        => $id_slide,
                                    'id_lang'         => $k,
                                    'name_one'        => $s['name_one'],
                                    'name_two'        => $s['name_two'],
                                    'description_one' => $s['description_one'],
                                    'description_two' => $s['description_two'],
                                    'bouton'          => $s['bouton'],
                                    'slug'            => $s['slug'],
                                ];
                                $builderSlidelang->set($datalang);
                                $builderSlidelang->where([$this->primaryKeySlideLang => $id_slide, 'id_lang' => $k]);
                                $builderSlidelang->update();
                            }
                        }
                    }
                } else {
                    $dataSlide = [
                        $this->primaryKeyLang => $diaporama->{$this->primaryKey},
                        'id_field'     => $slide,
                        'options'      => $v['options'],
                        'handle'       => 'slide' . $slide,
                        'color_bg'     => $v['color_bg'],
                        'order'        => $i,
                        'updated_at'   => date('y-m-d H:i:s')
                    ];
                    $builderSlide->set($dataSlide);
                    $builderSlide->where([$this->primaryKeyLang => $diaporama->{$this->primaryKey}, 'id_field' => $slide]);
                    $builderSlide->update();

                    $builderSlidelang = $db->table($this->tableSlideLang);
                    foreach ($v['lang'] as $k => $s) {
                        // print_r([$this->primaryKeySlideLang => $slideOnly->{$this->primaryKeySlide}, 'id_lang' => $k]);
                        //exit;
                        $slideLangOnly =  $builderSlidelang->where([$this->primaryKeySlideLang => $slideOnly->{$this->primaryKeySlide}, 'id_lang' => $k])->get()->getRow();
                        // echo '<pre>';
                        // print_r($slideLangOnly);
                        // echo '</pre>';
                        if (empty($slideLangOnly)) {
                            $datalang = [
                                $this->primaryKeySlideLang => $slideOnly->{$this->primaryKeySlide},
                                'id_lang'                  => $k,
                                'name_one'                 => $s['name_one'],
                                'name_two'                 => $s['name_two'],
                                'description_one'          => $s['description_one'],
                                'description_two'          => $s['description_two'],
                                'bouton'                   => $s['bouton'],
                                'slug'                     => $s['slug'],
                            ];

                            $builderSlidelang->insert($datalang);
                        } else {
                            $datalang = [
                                $this->primaryKeySlideLang => $slideOnly->{$this->primaryKeySlide},
                                'id_lang'                  => $k,
                                'name_one'                 => $s['name_one'],
                                'name_two'                 => $s['name_two'],
                                'description_one'          => $s['description_one'],
                                'description_two'          => $s['description_two'],
                                'bouton'                   => $s['bouton'],
                                'slug'                     => $s['slug'],
                            ];


                            $builderSlidelang->set($datalang);
                            $builderSlidelang->where([$this->primaryKeySlideLang => $slideOnly->{$this->primaryKeySlide}, 'id_lang' => $k]);
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
