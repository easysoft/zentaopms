<?php
namespace zin;

class tabPane extends wg
{
    protected static $defineProps = array(
        'isActive?:bool=false',
    );

    protected function build()
    {
        $isActive = $this->prop('isActive');
        $className = $isActive ? 'tab-pane active' : 'tab-pane';
        return div
        (
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            setClass($className),
            $this->children()
        );
    }
}
