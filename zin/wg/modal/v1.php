<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'modaldialog' . DS . 'v1.php';

class modal extends modaldialog
{
    static $defineProps = 'id?: string="$GID", modalProps?: array';

    protected function build()
    {
        list($id, $modalProps) = $this->prop(array('id', 'modalProps'));
        return div
        (
            setClass('modal'),
            set::id($id),
            set($modalProps),
            parent::build()
        );
    }
}
