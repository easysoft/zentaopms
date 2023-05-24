<?php
declare(strict_types=1);
namespace zin;

class detailBody extends wg
{
    protected function build()
    {
        return div
        (
            setClass('detail-body canvas shadow rounded flex'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children()
        );
    }
}
