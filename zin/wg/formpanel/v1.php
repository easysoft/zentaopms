<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'panel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formgroup' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formrow' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'form' . DS . 'v1.php';

class formPanel extends panel
{
    protected static $defineProps =
    [
        'method?: string',
        'url?: string',
        'actions?: array',
        'target?: string',
        'items?: array',
        'grid?: bool',
        'labelWidth?: number',
    ];

    protected static $defaultProps =
    [
        'class'         => 'panel-form rounded-md shadow ring-0 canvas px-4 pb-4 mb-4 mx-auto',
        'size'          => 'lg'
    ];

    protected function created()
    {
        $this->setDefaultProps(['title' => data('title')]);
    }

    protected function buildBody()
    {
        $props = $this->props->pick(['method', 'url', 'actions', 'target', 'items', 'grid', 'labelWidth']);
        return div
        (
            setClass('panel-body'),
            new form
            (
                set($props),
                $this->children()
            )
        );
    }
}
