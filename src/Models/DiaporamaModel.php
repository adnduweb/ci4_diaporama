<?php

namespace Adnduweb\Ci4_diaporama\Models;

use CodeIgniter\Model;
use Adnduweb\Ci4_diaporama\Entities\Diaporama;
use Adnduweb\Ci4_diaporama\Entities\Slide;

class DiaporamaModel extends Model
{
    use \Tatter\Relations\Traits\ModelTrait, \Adnduweb\Ci4_logs\Traits\AuditsTrait, \App\Models\BaseModel;

    protected $afterInsert         = ['auditInsert'];
    protected $afterUpdate         = ['auditUpdate'];
    protected $afterDelete         = ['auditDelete'];
    protected $table               = 'diaporamas';
    protected $tableLang           = 'diaporamas_langs';
    protected $tableSlide          = 'diaporamas_slides';
    protected $tableSlideLang      = 'diaporamas_slides_langs';
    protected $with                = ['diaporamas_langs'];
    protected $without             = [];
    protected $primaryKey          = 'id';
    protected $primaryKeyLang      = 'diaporama_id';
    protected $primaryKeySlideLang = 'slide_id';
    protected $returnType          = Diaporama::class;
    protected $useSoftDeletes      = true;
    protected $allowedFields       = ['id_parent', 'active', 'handle', 'dimensions', 'transparent_mask', 'transparent_mask_color_bg', 'force_height', 'center_img', 'bouton_diapo', 'order'];
    protected $useTimestamps       = true;
    protected $validationRules     = [];
    protected $validationMessages  = [];
    protected $skipValidation      = false;
    protected $searchKtDatatable  = ['name', 'description_short', 'created_at'];

    public function __construct()
    {
        parent::__construct();
        $this->builder            = $this->db->table('diaporamas');
        $this->builder_lang       = $this->db->table('diaporamas_langs');
        $this->builder_slide      = $this->db->table('diaporamas_slides');
        $this->builder_slide_lang = $this->db->table('diaporamas_slides_langs');
    }

    public function getListByMenu()
    {
        $instance = [];
        $this->builder->select($this->table . '.id_diaporama, slug, name, created_at');
        $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->builder->where('deleted_at IS NULL AND id_lang = ' . service('switchlanguage')->getIdLocale());
        $this->builder->orderBy($this->table . '.id_diaporama DESC');
        $diaporama = $this->builder->get()->getResult();
        if (!empty($diaporama)) {
            foreach ($diaporama as $page) {
                $instance[] = new Diaporama((array) $page);
            }
        }
        //echo $this->builder->getCompiledSelect(); exit;
        return $instance;
    }

    public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        $diaporamas =  $this->getBaseAllList($page, $perpage, $sort, $query, $this->searchKtDatatable);
        // In va chercher les b_categories_table
        if (!empty($diaporamas)) {
            $i = 0;
            foreach ($diaporamas as $diaporama) {
                $LangueDisplay = [];
                foreach (service('switchlanguage')->getArrayLanguesSupported() as $k => $v) {
                    if ($diaporama->id_lang == $v) {
                        //Existe = 
                        $LangueDisplay[$k] = true;
                    } else {
                        $LangueDisplay[$k] = false;
                    }
                }
                $diaporamas[$i]->languages = $LangueDisplay;
                $i++;
            }
        }

        //echo $this->b_posts_table->getCompiledSelect(); exit;
        return $diaporamas;
    }


    public function getAllDiaporamaLight()
    {
        $this->builder->select($this->table . '.' . $this->primaryKey . ', name');
        $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->builder->where('deleted_at IS NULL AND id_lang = ' . service('switchlanguage')->getIdLocale());
        return $this->builder->get()->getResult();
    }

    public function getDiaporamaFront(int $id_diaporama, int $lang)
    {
        $instance['originalSettings'] = [];
        $instance['originalSlides'] = [];
        $this->builder->select();
        $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->builder->where('deleted_at IS NULL AND ' . $this->table . '.' . $this->primaryKey . ' = ' . $id_diaporama . ' AND id_lang = ' . $lang);
        $instance['originalSettings'] = $this->builder->get()->getRow();
        if (!empty($instance['originalSettings'])) {
            $this->builder_slide->select();
            $this->builder_slide->join($this->tableSlideLang, $this->tableSlide . '.' . $this->primaryKey . ' = ' . $this->tableSlideLang . '.' . $this->primaryKeySlideLang);
            $this->builder_slide->where('deleted_at IS NULL AND ' . $this->tableSlide . '.' . $this->primaryKeyLang . '=' . $instance['originalSettings']->{$this->primaryKey} . ' AND id_lang=' . $lang);
            $this->builder_slide->orderBy($this->tableSlide . '.order ASC');
            // echo $this->builder_slide->getCompiledSelect();
            // exit;
            $slides = $this->builder_slide->get()->getResult();
            // print_r($slides); exit;
            if (!empty($slides)) {
                foreach ($slides as $slide) {
                    $instance['originalSlides'][] = new Slide((array) $slide);
                }
            }
        }
        return $instance;
    }
}
