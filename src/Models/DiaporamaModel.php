<?php

namespace Adnduweb\Ci4_diaporama\Models;

use CodeIgniter\Model;
use Adnduweb\Ci4_diaporama\Entities\Diaporama;
use Adnduweb\Ci4_diaporama\Entities\Slide;

class DiaporamaModel extends Model
{
    use \Tatter\Relations\Traits\ModelTrait;
    use \Adnduweb\Ci4_logs\Traits\AuditsTrait;
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
    protected $allowedFields       = ['id_parent', 'active', 'handle', 'dimensions', 'transparent_mask', 'transparent_mask_color_bg', 'bouton_diapo', 'order'];
    protected $useTimestamps       = true;
    protected $validationRules     = [];
    protected $validationMessages  = [];
    protected $skipValidation      = false;

    public function __construct()
    {
        parent::__construct();
        $this->diaporamas_table            = $this->db->table('diaporamas');
        $this->diaporamas_table_lang       = $this->db->table('diaporamas_langs');
        $this->diaporamas_table_slide      = $this->db->table('diaporamas_slides');
        $this->diaporamas_table_slide_lang = $this->db->table('diaporamas_slides_langs');
    }

    public function getListByMenu()
    {
        $instance = [];
        $this->diaporamas_table->select($this->table . '.id_diaporama, slug, name, created_at');
        $this->diaporamas_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->diaporamas_table->where('deleted_at IS NULL AND id_lang = ' . service('switchlanguage')->getIdLocale());
        $this->diaporamas_table->orderBy($this->table . '.id_diaporama DESC');
        $diaporama = $this->diaporamas_table->get()->getResult();
        if (!empty($diaporama)) {
            foreach ($diaporama as $page) {
                $instance[] = new Diaporama((array) $page);
            }
        }
        //echo $this->diaporamas_table->getCompiledSelect(); exit;
        return $instance;
    }

    public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        $this->diaporamas_table->select();
        $this->diaporamas_table->select('created_at as date_create_at');
        $this->diaporamas_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        if (isset($query[0]) && is_array($query)) {
            $this->diaporamas_table->where('deleted_at IS NULL AND (name LIKE "%' . $query[0] . '%" OR description_short LIKE "%' . $query[0] . '%") AND id_lang = ' . service('switchlanguage')->getIdLocale());
            $this->diaporamas_table->limit(0, $page);
        } else {
            $this->diaporamas_table->where('deleted_at IS NULL AND id_lang = ' . service('switchlanguage')->getIdLocale());
            $page = ($page == '1') ? '0' : (($page - 1) * $perpage);
            $this->diaporamas_table->limit($perpage, $page);
        }


        $this->diaporamas_table->orderBy($sort['field'] . ' ' . $sort['sort']);

        $groupsRow = $this->diaporamas_table->get()->getResult();

        //echo $this->diaporamas_table->getCompiledSelect(); exit;
        return $groupsRow;
    }

    public function getAllCount(array $sort, array $query)
    {
        $this->diaporamas_table->select($this->table . '.' . $this->primaryKey);
        $this->diaporamas_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        if (isset($query[0]) && is_array($query)) {
            $this->diaporamas_table->where('deleted_at IS NULL AND (name LIKE "%' . $query[0] . '%" OR description_short LIKE "%' . $query[0] . '%") AND id_lang = ' . service('switchlanguage')->getIdLocale());
        } else {
            $this->diaporamas_table->where('deleted_at IS NULL AND id_lang = ' . service('switchlanguage')->getIdLocale());
        }

        $this->diaporamas_table->orderBy($sort['field'] . ' ' . $sort['sort']);

        $diaporama = $this->diaporamas_table->get();
        //echo $this->diaporamas_table->getCompiledSelect(); exit;
        return $diaporama->getResult();
    }

    public function getAllDiaporamaLight()
    {
        $this->diaporamas_table->select($this->table . '.' . $this->primaryKey . ', name');
        $this->diaporamas_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->diaporamas_table->where('deleted_at IS NULL AND id_lang = ' . service('switchlanguage')->getIdLocale());
        return $this->diaporamas_table->get()->getResult();
    }

    public function getDiaporamaFront(int $id_diaporama, int $lang)
    {
        $instance['originalSettings'] = [];
        $instance['originalSlides'] = [];
        $this->diaporamas_table->select();
        $this->diaporamas_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->diaporamas_table->where('deleted_at IS NULL AND ' . $this->table . '.' . $this->primaryKey . ' = ' . $id_diaporama . ' AND id_lang = ' . $lang);
        $instance['originalSettings'] = $this->diaporamas_table->get()->getRow();
        if (!empty($instance['originalSettings'])) {
            $this->diaporamas_table_slide->select();
            $this->diaporamas_table_slide->join($this->tableSlideLang, $this->tableSlide . '.' . $this->primaryKey . ' = ' . $this->tableSlideLang . '.' . $this->primaryKeySlideLang);
            $this->diaporamas_table_slide->where('deleted_at IS NULL AND ' . $this->tableSlide . '.' . $this->primaryKeyLang . '=' . $instance['originalSettings']->{$this->primaryKey} . ' AND id_lang=' . $lang);
            $this->diaporamas_table_slide->orderBy($this->tableSlide . '.order ASC');
            // echo $this->diaporamas_table_slide->getCompiledSelect();
            // exit;
            $slides = $this->diaporamas_table_slide->get()->getResult();
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
