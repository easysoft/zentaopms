<?php

namespace zin;

class center extends wg
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
        return div(
            setClass("flex justify-center items-center"),
            $this->block('inner'),
        );
    }
}
