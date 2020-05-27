<?php

namespace Adnduweb\Ci4_diaporama\Models;

use CodeIgniter\Model;
use Adnduweb\Ci4_diaporama\Entities\Diaporama;
use Adnduweb\Ci4_diaporama\Entities\Slide;

class DiaporamaModel extends Model
{
    use \Tatter\Relations\Traits\ModelTrait;
    use \Adnduweb\Ci4_logs\Traits\AuditsTrait;
    protected $afterInsert        = ['auditInsert'];
    protected $afterUpdate        = ['auditUpdate'];
    protected $afterDelete        = ['auditDelete'];
    protected $table              = 'diaporamas';
    protected $tableLang          = 'diaporamas_langs';
    protected $tableSlide         = 'diaporamas_slides';
    protected $tableSlideLang     = 'diaporamas_slides_langs';
    protected $with               = ['diaporamas_langs'];
    protected $without            = [];
    protected $primaryKey         = 'id_diaporama';
    protected $returnType         = Diaporama::class;
    protected $useSoftDeletes     = true;
    protected $allowedFields      = ['id_parent', 'active', 'handle', 'dimensions', 'transparent_mask', 'transparent_mask_color_bg', 'bouton_diapo', 'order'];
    protected $useTimestamps      = true;
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function __construct()
    {
        parent::__construct();
        $this->diaporama = $this->db->table('diaporamas');
        $this->diaporama_lang = $this->db->table('diaporamas_langs');
        $this->diaporama_slide = $this->db->table('diaporamas_slides');
        $this->diaporama_slide_lang = $this->db->table('diaporamas_slides_langs');
    }

    public function getListByMenu()
    {
        $instance = [];
        $this->diaporama->select($this->table . '.id_diaporama, slug, name, created_at');
        $this->diaporama->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_diaporama');
        $this->diaporama->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        $this->diaporama->orderBy($this->table . '.id_diaporama DESC');
        $diaporamas = $this->diaporama->get()->getResult();
        if (!empty($diaporamas)) {
            foreach ($diaporamas as $page) {
                $instance[] = new Diaporama((array) $page);
            }
        }
        //echo $this->diaporama->getCompiledSelect(); exit;
        return $instance;
    }

    public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        $this->diaporama->select();
        $this->diaporama->select('created_at as date_create_at');
        $this->diaporama->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_diaporama');
        if (isset($query[0]) && is_array($query)) {
            $this->diaporama->where('deleted_at IS NULL AND (name LIKE "%' . $query[0] . '%" OR description_short LIKE "%' . $query[0] . '%") AND id_lang = ' . service('settings')->setting_id_lang);
            $this->diaporama->limit(0, $page);
        } else {
            $this->diaporama->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
            $page = ($page == '1') ? '0' : (($page - 1) * $perpage);
            $this->diaporama->limit($perpage, $page);
        }


        $this->diaporama->orderBy($sort['field'] . ' ' . $sort['sort']);

        $groupsRow = $this->diaporama->get()->getResult();

        //echo $this->diaporama->getCompiledSelect(); exit;
        return $groupsRow;
    }

    public function getAllCount(array $sort, array $query)
    {
        $this->diaporama->select($this->table . '.' . $this->primaryKey);
        $this->diaporama->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_diaporama');
        if (isset($query[0]) && is_array($query)) {
            $this->diaporama->where('deleted_at IS NULL AND (name LIKE "%' . $query[0] . '%" OR description_short LIKE "%' . $query[0] . '%") AND id_lang = ' . service('settings')->setting_id_lang);
        } else {
            $this->diaporama->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        }

        $this->diaporama->orderBy($sort['field'] . ' ' . $sort['sort']);

        $diaporamas = $this->diaporama->get();
        //echo $this->diaporama->getCompiledSelect(); exit;
        return $diaporamas->getResult();
    }

    public function getAllDiaporamaLight()
    {
        $this->diaporama->select($this->table . '.' . $this->primaryKey . ', name');
        $this->diaporama->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_diaporama');
        $this->diaporama->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        return $this->diaporama->get()->getResult();
    }

    public function getDiaporamaFront(int $id_diaporama, int $lang)
    {
        $instance['originalSettings'] = [];
        $instance['originalSlides'] = [];
        $this->diaporama->select();
        $this->diaporama->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_diaporama');
        $this->diaporama->where('deleted_at IS NULL AND ' . $this->table . '.id_diaporama = ' . $id_diaporama . ' AND id_lang = ' . $lang);
        $instance['originalSettings'] = $this->diaporama->get()->getRow();
        if (!empty($instance['originalSettings'])) {
            $this->diaporama_slide->select();
            $this->diaporama_slide->join($this->tableSlideLang, $this->tableSlide . '.id_slide = ' . $this->tableSlideLang . '.id_slide');
            $this->diaporama_slide->where('deleted_at IS NULL AND ' . $this->tableSlide . '.' . $this->primaryKey . '=' . $instance['originalSettings']->id_diaporama . ' AND id_lang=' . $lang);
            $this->diaporama_slide->orderBy($this->tableSlide . '.order ASC');
            // echo $this->diaporama_slide->getCompiledSelect();
            // exit;
            $slides = $this->diaporama_slide->get()->getResult();
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
