<?php

namespace Adnduweb\Ci4_diaporama\Models;

use CodeIgniter\Model;
use Adnduweb\Ci4_diaporama\Entities\Diaporama;

class DiaporamaModel extends Model
{
    use \Tatter\Relations\Traits\ModelTrait;
    use \Adnduweb\Ci4_logs\Traits\AuditsTrait;
    protected $afterInsert        = ['auditInsert'];
    protected $afterUpdate        = ['auditUpdate'];
    protected $afterDelete        = ['auditDelete'];
    protected $table              = 'diaporamas';
    protected $tableLang          = 'diaporamas_langs';
    protected $with               = ['diaporamas_langs'];
    protected $without            = [];
    protected $primaryKey         = 'id_page';
    protected $returnType         = Diaporama::class;
    protected $useSoftDeletes     = true;
    protected $allowedFields      = ['id_parent', 'template', 'active', 'no_follow_no_index', 'handle', 'order'];
    protected $useTimestamps      = true;
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function __construct()
    {
        parent::__construct();
        $this->page = $this->db->table('diaporamas');
        $this->page_lang = $this->db->table('diaporamas_langs');
    }

    public function getListByMenu()
    {
        $instance = [];
        $this->page->select($this->table . '.id_page, slug, name, created_at');
        $this->page->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_page');
        $this->page->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        $this->page->orderBy($this->table . '.id_page DESC');
        $diaporamas = $this->page->get()->getResult();
        if (!empty($diaporamas)) {
            foreach ($diaporamas as $page) {
                $instance[] = new Diaporama((array) $page);
            }
        }
        //echo $this->page->getCompiledSelect(); exit;
        return $instance;
    }

    public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        $this->page->select();
        $this->page->select('created_at as date_create_at');
        $this->page->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_page');
        if (isset($query['generalSearch']) && !empty($query['generalSearch'])) {
            $this->page->where('deleted_at IS NULL AND (name LIKE "%' . $query['generalSearch'] . '%" OR login_destination LIKE "%' . $query['generalSearch'] . '%") AND id_lang = ' . service('settings')->setting_id_lang);
            $this->page->limit(0, $page);
        } else {
            $this->page->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
            $page = ($page == '1') ? '0' : (($page - 1) * $perpage);
            $this->page->limit($perpage, $page);
        }


        $this->page->orderBy($sort['field'] . ' ' . $sort['sort']);

        $groupsRow = $this->page->get()->getResult();

        //echo $this->page->getCompiledSelect(); exit;
        return $groupsRow;
    }

    public function getAllCount(array $sort, array $query)
    {
        $this->page->select($this->table . '.' . $this->primaryKey);
        $this->page->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_page');
        if (isset($query['generalSearch']) && !empty($query['generalSearch'])) {
            $this->page->where('deleted_at IS NULL AND (name LIKE "%' . $query['generalSearch'] . '%" OR login_destination LIKE "%' . $query['generalSearch'] . '%") AND id_lang = ' . service('settings')->setting_id_lang);
        } else {
            $this->page->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        }

        $this->page->orderBy($sort['field'] . ' ' . $sort['sort']);

        $diaporamas = $this->page->get();
        //echo $this->page->getCompiledSelect(); exit;
        return $diaporamas->getResult();
    }

    public function getPageBySlug($slug)
    {
        $this->page->select();
        $this->page->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_page');
        $this->page->where('slug="' . $slug. '"');
        $page = $this->page->get()->getRowArray();
        if ($page['active'] == '1')
            return $page;
        return false;
    }

    public function getPageByIdInMenu($id, int $id_lang)
    {

        $this->page->select();
        $this->page->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_page');
        $this->page->where([$this->table . '.id_page' => $id, 'id_lang' => $id_lang]);
        $page = $this->page->get()->getRow();
        if ($page->active == '1')
            return $page;
        return false;
    }
}
