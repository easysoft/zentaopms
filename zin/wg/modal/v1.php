<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'modaldialog' . DS . 'v1.php';

class modal extends modalDialog
{
    static $defineProps = 'id?: string="$GID", modalProps?: array=[]';

    protected function build()
    {
        list($id, $modalProps) = $this->prop(array('id', 'modalProps'));

        $this->setProp($modalProps);

        return div
        (
            setClass('modal'),
            set::id($id),
            set($this->props->skip(array_merge(array_keys($modalProps), array_keys(static::getDefinedProps())))),
            parent::build()
        );
    }
}
