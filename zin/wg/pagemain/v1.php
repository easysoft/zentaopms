<?php
namespace zin;

class pagemain extends wg
{
    public function onAddChild($child)
    {
        if($child instanceof wg) {
            $this->addToBlock('inner', $child);
            return false;
        }
        return null;
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
