<?php
namespace zin;

class pagemain extends wg
{
    public function addChild($child)
    {
        if($child instanceof wg) $this->addToBlock('inner', $child);
        else $this->props->addChildren($child);
    }

    protected function build()
    {
        $pagemain = h::div(
            h::div(
                setClass('container'),
                $this->block('inner'),
            )
        );
        $pagemain->setDefaultProps(array('id' => 'main'));
        return $pagemain;
    }
}
