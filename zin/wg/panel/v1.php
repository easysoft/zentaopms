<?php
namespace zin;

class panel extends wg
{
    protected static $defineProps = array
    (
        /* Theme: primary, secondary, warning, danger, important. */
        'theme?:string="primary"',
    );

    private function getBlock($blockName)
    {
        if(!isset($this->blocks[$blockName])) return null;

        return div
        (
            setClass('panel-' . $blockName),
            $this->blocks[$blockName]
        );
    }

    protected function build()
    {
        return div
        (
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            setClass('panel'),
            setClass($this->prop('theme')),

            $this->getBlock('heading'),
            $this->getBlock('body'),
            $this->getBlock('footer'),
            $this->children()
        );
    }
}
