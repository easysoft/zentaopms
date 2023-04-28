<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'page' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formpanel' . DS . 'v1.php';

class pageForm extends page
{
    static $defineProps = 'formPanel?:array';

    public function children()
    {
        return array(
            formPanel(set($this->prop('formPanel')), parent::children())
        );
    }
}
