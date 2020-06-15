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
    protected $table              = 'diaporama';
    protected $tableLang          = 'diaporama_lang';
    protected $tableSlide         = 'diaporama_slide';
    protected $tableSlideLang     = 'diaporama_slide_lang';
    protected $with               = ['diaporama_lang'];
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
        $this->diaporama_table            = $this->db->table('diaporama');
        $this->diaporama_table_lang       = $this->db->table('diaporama_lang');
        $this->diaporama_table_slide      = $this->db->table('diaporama_slide');
        $this->diaporama_table_slide_lang = $this->db->table('diaporama_slide_lang');
    }

    public function getListByMenu()
    {
        $instance = [];
        $this->diaporama_table->select($this->table . '.id_diaporama, slug, name, created_at');
        $this->diaporama_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_diaporama');
        $this->diaporama_table->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        $this->diaporama_table->orderBy($this->table . '.id_diaporama DESC');
        $diaporama = $this->diaporama_table->get()->getResult();
        if (!empty($diaporama)) {
            foreach ($diaporama as $page) {
                $instance[] = new Diaporama((array) $page);
            }
        }
        //echo $this->diaporama_table->getCompiledSelect(); exit;
        return $instance;
    }

    public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        $this->diaporama_table->select();
        $this->diaporama_table->select('created_at as date_create_at');
        $this->diaporama_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_diaporama');
        if (isset($query[0]) && is_array($query)) {
            $this->diaporama_table->where('deleted_at IS NULL AND (name LIKE "%' . $query[0] . '%" OR description_short LIKE "%' . $query[0] . '%") AND id_lang = ' . service('settings')->setting_id_lang);
            $this->diaporama_table->limit(0, $page);
        } else {
            $this->diaporama_table->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
            $page = ($page == '1') ? '0' : (($page - 1) * $perpage);
            $this->diaporama_table->limit($perpage, $page);
        }


        $this->diaporama_table->orderBy($sort['field'] . ' ' . $sort['sort']);

        $groupsRow = $this->diaporama_table->get()->getResult();

        //echo $this->diaporama_table->getCompiledSelect(); exit;
        return $groupsRow;
    }

    public function getAllCount(array $sort, array $query)
    {
        $this->diaporama_table->select($this->table . '.' . $this->primaryKey);
        $this->diaporama_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_diaporama');
        if (isset($query[0]) && is_array($query)) {
            $this->diaporama_table->where('deleted_at IS NULL AND (name LIKE "%' . $query[0] . '%" OR description_short LIKE "%' . $query[0] . '%") AND id_lang = ' . service('settings')->setting_id_lang);
        } else {
            $this->diaporama_table->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        }

        $this->diaporama_table->orderBy($sort['field'] . ' ' . $sort['sort']);

        $diaporama = $this->diaporama_table->get();
        //echo $this->diaporama_table->getCompiledSelect(); exit;
        return $diaporama->getResult();
    }

    public function getAllDiaporamaLight()
    {
        $this->diaporama_table->select($this->table . '.' . $this->primaryKey . ', name');
        $this->diaporama_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_diaporama');
        $this->diaporama_table->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        return $this->diaporama_table->get()->getResult();
    }

    public function getDiaporamaFront(int $id_diaporama, int $lang)
    {
        $instance['originalSettings'] = [];
        $instance['originalSlides'] = [];
        $this->diaporama_table->select();
        $this->diaporama_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_diaporama');
        $this->diaporama_table->where('deleted_at IS NULL AND ' . $this->table . '.id_diaporama = ' . $id_diaporama . ' AND id_lang = ' . $lang);
        $instance['originalSettings'] = $this->diaporama_table->get()->getRow();
        if (!empty($instance['originalSettings'])) {
            $this->diaporama_table_slide->select();
            $this->diaporama_table_slide->join($this->tableSlideLang, $this->tableSlide . '.id_slide = ' . $this->tableSlideLang . '.id_slide');
            $this->diaporama_table_slide->where('deleted_at IS NULL AND ' . $this->tableSlide . '.' . $this->primaryKey . '=' . $instance['originalSettings']->id_diaporama . ' AND id_lang=' . $lang);
            $this->diaporama_table_slide->orderBy($this->tableSlide . '.order ASC');
            // echo $this->diaporama_table_slide->getCompiledSelect();
            // exit;
            $slides = $this->diaporama_table_slide->get()->getResult();
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
