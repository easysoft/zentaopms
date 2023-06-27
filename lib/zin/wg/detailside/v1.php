<?php
declare(strict_types=1);
namespace zin;

class detailSide extends wg
{
    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function build(): wg
    {
        return div
        (
            setClass('detail-side flex-none px-6 py-3'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children()
        );
    }
}
