<?php
declare(strict_types=1);
namespace zin;

class center extends wg
{
    protected function build()
    {
        return div
        (
            setClass("center"),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
