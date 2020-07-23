<?php

namespace Adnduweb\Ci4_diaporama\Models;

use CodeIgniter\Model;
use Adnduweb\Ci4_diaporama\Entities\Slide;

class SlideModel extends Model
{
    use \Tatter\Relations\Traits\ModelTrait;
    use \Adnduweb\Ci4_logs\Traits\AuditsTrait;
    protected $afterInsert         = ['auditInsert'];
    protected $afterUpdate         = ['auditUpdate'];
    protected $afterDelete         = ['auditDelete'];
    protected $table               = 'diaporamas_slides';
    protected $tableLang           = 'diaporamas_slides_langs';
    protected $with                = ['diaporamas_slides_langs'];
    protected $without             = [];
    protected $primaryKey          = 'id';
    protected $primaryKeyLang      = 'slide_id';
    protected $primaryKeyDiapoLang = 'diaporama_id';
    protected $returnType          = Slide::class;
    protected $localizeFile        = 'Adnduweb\Ci4_diaporama\Models\SlideModel';
    protected $useSoftDeletes      = false;
    protected $allowedFields       = ['diaporama_id', 'id_field', 'options', 'handle', 'color_bg', 'order'];
    protected $useTimestamps       = true;
    protected $validationRules     = [];
    protected $validationMessages  = [];
    protected $skipValidation      = false;

    public function __construct()
    {
        parent::__construct();
        $this->diaporamas_slides      = $this->db->table('diaporamas_slides');
        $this->diaporamas_slides_langs = $this->db->table('diaporamas_slides_langs');
    }

    public function getSlidesByDiaporama(int $id_diaporama)
    {
        //echo $this->tableLang ; exit;
        $instance = [];
        $this->diaporamas_slides->select();
        $this->diaporamas_slides->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->diaporamas_slides->where('deleted_at IS NULL AND ' . $this->primaryKeyDiapoLang . ' = ' . $id_diaporama . ' AND id_lang=' . service('switchlanguage')->getIdLocale());
        $this->diaporamas_slides->orderBy($this->table . '.order ASC');
        $slides = $this->diaporamas_slides->get()->getResult();
        if (!empty($slides)) {
            foreach ($slides as $slide) {
                $instance[] = new Slide((array) $slide);
            }
        }
        return $instance;
    }
}
