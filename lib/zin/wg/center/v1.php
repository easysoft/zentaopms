<?php
declare(strict_types=1);
namespace zin;

class center extends wg
{
    protected function build(): wg
    {
        return div
        (
            setClass("flex justify-center items-center"),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
