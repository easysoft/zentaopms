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
            setClass('detail-side px-5 py-4 flex-none'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children()
        );
    }
}
