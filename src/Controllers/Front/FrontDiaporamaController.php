<?php

namespace Adnduweb\Ci4_diaporama\Controllers\Front;

use CodeIgniter\API\ResponseTrait;
use Adnduweb\Ci4_diaporama\Entities\Diaporama;
use Adnduweb\Ci4_diaporama\Models\DiaporamaModel;

class FrontDiaporamaController extends \App\Controllers\Front\FrontController
{
    use \App\Traits\BuilderModelTrait;
    use \App\Traits\ModuleTrait;

    public $name_module = 'pages';
    protected $slugModule;
    protected $page;

    public function __construct()
    {
        parent::__construct();
        $this->tableModel  = new PageModel();
        $this->idModule  = $this->getIdModule();
    }
    public function index()
    {
    }

    public function show($slug)
    {
        $loccale = 1;
        $setting_supportedLocales = unserialize(service('Settings')->setting_supportedLocales);
        foreach ($setting_supportedLocales as $setting_supportedLocale) {
            $v = explode('|', $setting_supportedLocale);
            if ($this->request->getLocale() == $v[1]) {
                $loccale = $v[0];
            }
        }
        $this->page = $this->tableModel->getPageBySlug($slug);
        if (empty($this->page)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(lang('Core.Cannot find the page item : {0}', [$slug]));
        }
        $this->data['page'] = new Page($this->page);

        $this->data['no_follow_no_index'] = ($this->data['page']->no_follow_no_index == 0) ?  'index follow' :  'no-index no-follow';
        $this->data['id']  = str_replace('/', '', $this->data['page']->slug);
        $this->data['class'] = $this->data['class'] . ' ' .  str_replace('/', '', $this->data['page']->slug) . ' ' .  str_replace('/', '', $this->data['page']->template);
        $this->data['meta_title'] = $this->data['page']->meta_title;
        $this->data['meta_description'] = $this->data['page']->meta_description;
        $builder = $this->getBuilderIdItem($this->data['page']->id_page, $this->idModule);
        if (!empty($builder)) {
            $this->data['page']->builder = $builder;
            $temp = [];
            foreach ($this->data['page']->builder as $builder) {
                $temp[$builder->order] = $builder;
            }
            ksort($temp);
            $this->data['page']->builder = $temp;
        }

        // print_r($this->data['pageContent']); exit;

        if ($this->data['page']->template == 'code') {
            return view($this->get_current_theme_view($this->data['page']->handle, 'default'), $this->data);
        } else {
            return view($this->get_current_theme_view('page', 'Adnduweb/Ci4_diaporama'), $this->data);
        }
    }
}
