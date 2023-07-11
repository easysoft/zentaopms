<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'page' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formpanel' . DS . 'v1.php';

class pageForm extends page
{
    protected static array $defineProps = array(
        'formPanel?: array'
    );

    public function children(): array
    {
        return array(
            formPanel(set($this->prop('formPanel')), parent::children())
        );
    }
}
