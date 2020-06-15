<?php

namespace Adnduweb\Ci4_diaporama\Models;

use CodeIgniter\Model;
use Adnduweb\Ci4_diaporama\Entities\Slide;

class SlideModel extends Model
{
    use \Tatter\Relations\Traits\ModelTrait;
    use \Adnduweb\Ci4_logs\Traits\AuditsTrait;
    protected $afterInsert        = ['auditInsert'];
    protected $afterUpdate        = ['auditUpdate'];
    protected $afterDelete        = ['auditDelete'];
    protected $table              = 'diaporama_slide';
    protected $tableLang          = 'diaporama_slide_lang';
    protected $with               = ['diaporama_slide_lang'];
    protected $without            = [];
    protected $primaryKey         = 'id_slide';
    protected $returnType         = Slide::class;
    protected $useSoftDeletes     = false;
    protected $allowedFields      = ['id_diaporama', 'id_field', 'options', 'handle', 'color_bg', 'order'];
    protected $useTimestamps      = true;
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function __construct()
    {
        parent::__construct();
        $this->diaporama_slide      = $this->db->table('diaporama_slide');
        $this->diaporama_slide_lang = $this->db->table('diaporama_slide_lang');
    }

    public function getSlidesByDiaporama(int $id_diaporama)
    {
        //echo $this->tableLang ; exit;
        $instance = [];
        $this->diaporama_slide->select();
        $this->diaporama_slide->join($this->tableLang, $this->table . '.id_slide = ' . $this->tableLang . '.id_slide');
        $this->diaporama_slide->where('deleted_at IS NULL AND id_diaporama = ' . $id_diaporama . ' AND id_lang=' . service('settings')->setting_id_lang);
        $this->diaporama_slide->orderBy($this->table . '.order ASC');
        $slides = $this->diaporama_slide->get()->getResult();
        if (!empty($slides)) {
            foreach ($slides as $slide) {
                $instance[] = new Slide((array) $slide);
            }
        }
        return $instance;
    }
}
