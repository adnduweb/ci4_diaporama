<?php

namespace Adnduweb\Ci4_diaporama\Entities;

use CodeIgniter\Entity;
use App\Entities\Media;

class Slide extends Entity
{
    use \Tatter\Relations\Traits\EntityTrait;
    protected $table          = 'diaporamas_slides';
    protected $tableLang      = 'diaporamas_slides_langs';
    protected $primaryKey     = 'id_slide';

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
        return $this->id_slide ?? null;
    }
    public function getNameOne()
    {
        return $this->attributes['name_one'] ?? null;
    }

    public function getNameLang(int $id_lang)
    {
        foreach ($this->diaporamas_slides_langs as $lang) {
            if ($id_lang == $lang->id_lang) {
                return $lang->name ?? null;
            }
        }
    } 

    public function getAttrOptionsImage()
    {
        if (!empty($this->attributes['options'])) {
            $options = json_decode($this->attributes['options']);
            $db      = \Config\Database::connect();
            $builder = $db->table('medias');
            $image =  $builder->where(['id_media' => $options->media->id_media])->get()->getRow();
            $media = new Media((array) $image);
            return $media->namePathFile('thumbnail');
        }
        return null;
    }

    public function getAttrOptions()
    {
        if (!empty($this->attributes['options'])) {
            return json_decode($this->attributes['options']);
        }
        return null;
    }


    public function _prepareLang()
    {
        $lang = [];
        if (!empty($this->id_slide)) {
            foreach ($this->diaporamas_slides_langs as $tabs_lang) {
                $lang[$tabs_lang->id_lang] = $tabs_lang;
            }
        }
        return $lang;
    }

}
