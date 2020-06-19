<?php

namespace Adnduweb\Ci4_diaporama\Entities;

use CodeIgniter\Entity;
use App\Entities\Media;

class Slide extends Entity
{
    use \Tatter\Relations\Traits\EntityTrait;
    use \App\Traits\BuilderEntityTrait;
    protected $table          = 'diaporamas_slides';
    protected $tableLang      = 'diaporamas_slides_langs';
    protected $primaryKey     = 'id';
    protected $primaryKeyLang = 'slide_id';

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

    public function getIdSlide()
    {
        return $this->id ?? null;
    }
    public function getNameOne()
    {
        return $this->attributes['name_one'] ?? null;
    }

    public function getNameLang(int $id_lang)
    {
        foreach ($this->diaporama_slide_lang as $lang) {
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
            $image =  $builder->where(['id' => $options->media->id])->get()->getRow();
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
}
