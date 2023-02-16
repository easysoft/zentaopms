<?php
namespace zin;

class pagemain extends wg
{
    protected function build()
    {
        $pagemain = div(
            div(
                setClass('container'),
                set($this->props->skip(array_keys(static::getDefinedProps()))),
                $this->children()
            )
        );
        $pagemain->setDefaultProps(array('id' => 'main'));
        return $pagemain;
    }
}
