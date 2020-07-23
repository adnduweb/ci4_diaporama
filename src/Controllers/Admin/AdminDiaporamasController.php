<?php

namespace Adnduweb\Ci4_diaporama\Controllers\Admin;

use App\Controllers\Admin\AdminController;

use App\Libraries\AssetsBO;
use App\Libraries\Tools;
use Adnduweb\Ci4_diaporama\Entities\Diaporama;
use Adnduweb\Ci4_diaporama\Models\DiaporamaModel;
use Adnduweb\Ci4_diaporama\Entities\Slide;
use Adnduweb\Ci4_diaporama\Models\SlideModel;



class AdminDiaporamasController extends AdminController
{

    use \App\Traits\ModuleTrait, \Adnduweb\Ci4_diaporama\DiaporamaTrait;

     /**
     *  Module Object
     */
    public $module = true;

    /**
     * name controller
     */
    public $controller = 'diaporama';

    /**
     * Localize slug
     */
    public $pathcontroller  = '/diaporamas';

    /**
     * Localize namespace
     */
    public $namespace = 'Adnduweb/Ci4_diaporama';

    /**
     * Id Module
     */
    protected $idModule;

    /**
     * Localize slug
     */
    public $dirList  = 'diaporamas';

    /**
     * Display default list column
     */
    public $fieldList = 'name';

    /**
     * Bouton add
     */
    public $add = true;

    /**
     * Display Multilangue
     */
    public $multilangue = true;

    /**
     * Event fake data
     */
    public $fake = false;

    /**
     * Update item List
     */
    public $toolbarUpdate = true;

    /**
     * @var \App\Models\FormModel
     */
    public $tableModel;

    /**
     * Instance Object
     */
    protected $instances = [];


    /**
     * Page constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->controller_type = 'admindiaporamas';
        $this->tableModel      = new DiaporamaModel();
        $this->tableSlideModel = new SlideModel();
        $this->idModule        = $this->getIdModule();

        $this->data['paramJs']['baseSegmentAdmin'] = config('Diaporama')->urlMenuAdmin;

        $this->pathcontroller  = '/'.config('Diaporama')->urlMenuAdmin . $this->pathcontroller;
    }

    public function renderViewList()
    {
        //print_r(Service('currency')->Taxe());exit;
        AssetsBO::add_js([$this->get_current_theme_view('controllers/' . $this->dirList . '/js/list.js', 'default')]);
        $parent =  parent::renderViewList();
        if (is_object($parent) && $parent->getStatusCode() == 307) {
            return $parent;
        }
        return $parent;
    }

    public function ajaxProcessList()
    {
        $parent = parent::ajaxProcessList();
        return $this->respond($parent, 200, lang('Core.liste des diaporamas'));
    }

    public function renderForm($id = null)
    {
        AssetsBO::add_js([$this->get_current_theme_view('controllers/' . $this->dirList . '/js/outils.js', 'default')]);
        AssetsBO::add_js([$this->get_current_theme_view('controllers/medias/js/manager.js', 'default')]);

        if (is_null($id)) {
            $this->data['form'] = new Diaporama($this->request->getPost());
        } else {
            $this->data['form'] = $this->tableModel->where('id', $id)->first();
            if (empty($this->data['form'])) {
                Tools::set_message('danger', lang('Core.not_{0}_exist', [$this->item]), lang('Core.warning_error'));
                return redirect()->to('/' . env('CI_SITE_AREA') . '/public/diaporamas');
            }
            $this->data['form']->slides =  $this->getSlidesByDiaporama($id);
        }
        // print_r($this->data['form']);
        // exit;
        parent::renderForm($id);
        return view($this->get_current_theme_view('form', 'Adnduweb/Ci4_diaporama'), $this->data);
    }

    public function postProcessEdit($param)
    {
        //print_r($_POST); exit;
        // validate
        $this->validation->setRules(['lang.1.name' => 'required']);
        if (!$this->validation->run($this->request->getPost())) {
            Tools::set_message('danger', $this->validation->getErrors(), lang('Core.warning_error'));
            return redirect()->back()->withInput();
        }
        // Try to create the user
        $diaporama = new Diaporama($this->request->getPost());
        $diaporama->active = isset($diaporama->active) ? 1 : 0;
        $diaporama->transparent_mask = isset($diaporama->transparent_mask) ? 1 : 0;
        $diaporama->force_height = isset($diaporama->force_height) ? 1 : 0;
        $diaporama->center_img = isset($diaporama->center_img) ? 1 : 0;
        $diaporama->bouton_diapo = isset($diaporama->bouton_diapo) ? 1 : 0;
        $this->lang = $this->request->getPost('lang');

        if (!$this->tableModel->save($diaporama)) {
            Tools::set_message('danger', $this->tableModel->errors(), lang('Core.warning_error'));
            return redirect()->back()->withInput();
        }
        $diaporama->saveLang($this->lang, $diaporama->id);

        // On enregistre les slides
        $diaporama->saveSlide($this->request->getPost('slide'), $diaporama);

        // Success!
        Tools::set_message('success', lang('Core.save_data'), lang('Core.cool_success'));
        $redirectAfterForm = [
            'url'                   => '/' . env('CI_SITE_AREA') . '/public/diaporamas',
            'action'                => 'edit',
            'submithandler'         => $this->request->getPost('submithandler'),
            'id'                    => $diaporama->id,
        ];
        $this->redirectAfterForm($redirectAfterForm);
    }

    public function postProcessAdd()
    {
        $this->validation->setRules(['lang.1.name' => 'required']);
        if (!$this->validation->run($this->request->getPost())) {
            Tools::set_message('danger', $this->validation->getErrors(), lang('Core.warning_error'));
            return redirect()->back()->withInput();
        }
        // Try to create the user
        $diaporama = new Diaporama($this->request->getPost());
        $diaporama->active = isset($diaporama->active) ? 1 : 0;
        $diaporama->transparent_mask = isset($diaporama->transparent_mask) ? 1 : 0;
        $diaporama->force_height = isset($diaporama->force_height) ? 1 : 0;
        $diaporama->center_img = isset($diaporama->center_img) ? 1 : 0;
        $diaporama->bouton_diapo = isset($diaporama->bouton_diapo) ? 1 : 0;
        $diaporama->handle = uniforme(trim($this->request->getPost('handle')));

        if (!$this->tableModel->save($diaporama)) {
            Tools::set_message('danger', $this->tableModel->errors(), lang('Core.warning_error'));
            return redirect()->back()->withInput();
        }

        $diaporamaId = $this->tableModel->insertID();
        $this->lang = $this->request->getPost('lang');
        $diaporama->saveLang($this->lang, $diaporamaId);


        // Success!
        Tools::set_message('success', lang('Core.save_data'), lang('Core.cool_success'));
        $redirectAfterForm = [
            'url'                   => '/' . env('CI_SITE_AREA') . '/public/diaporamas',
            'action'                => 'add',
            'submithandler'         => $this->request->getPost('submithandler'),
            'id'                    => $diaporamaId,
        ];
        $this->redirectAfterForm($redirectAfterForm);
    }

    public function ajaxProcessUpdate()
    {
        if ($value = $this->request->getPost('value')) {
            $data = [];
            if (isset($value['selected']) && !empty($value['selected'])) {
                foreach ($value['selected'] as $selected) {

                    $data[] = [
                        'id'      => $selected,
                        'active' => $value['active'],
                    ];
                }
            }
            if ($this->tableModel->updateBatch($data, 'id')) {
                return $this->respond(['status' => true, 'message' => lang('Js.your_seleted_records_statuses_have_been_updated')], 200);
            } else {
                return $this->respond(['status' => false, 'database' => true, 'display' => 'modal', 'message' => lang('Js.aucun_enregistrement_effectue')], 200);
            }
        }
    }

    public function ajaxProcessDelete()
    {
        if ($value = $this->request->getPost('value')) {
            if (!empty($value['selected'])) {
                $itsme = false;
                foreach ($value['selected'] as $id) {

                    $this->tableModel->delete($id);
                }
                return $this->respond(['status' => true, 'type' => 'success', 'message' => lang('Js.your_selected_records_have_been_deleted')], 200);
            }
        }
        return $this->failUnauthorized(lang('Js.not_autorized'), 400);
    }

    public function ajaxProcessGetTemplateSlide()
    {
        if ($value = $this->request->getPost('value')) {
            $this->data['id_field'] =  $value['id_field'];
            $this->data['slide'] =  new Slide();
            $this->data['form'] = $this->tableModel->where('id', $value['id_diaporama'])->first();
            $html = view($this->get_current_theme_view('__form_section/slide', 'Adnduweb/Ci4_diaporama'), $this->data);

            $options = [
                'status' => true,
                'type' => 'success',
                'template_slide' => $html
            ];

            return $this->response->setJSON($options);
        }
    }

    public function ajaxProcessDeleteSlide()
    {
        if ($value = $this->request->getPost('value')) {
            $this->tableSlideModel->delete(['id_slide' => $value]);
            return $this->respond(['status' => true, 'type' => 'success', 'message' => lang('Js.your_selected_records_have_been_deleted')], 200);
        }
    }
}
