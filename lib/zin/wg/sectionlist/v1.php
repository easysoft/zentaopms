<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'section' . DS . 'v1.php';

class sectionList extends wg
{
    public function onBuildItem(wg $item): wg
    {
        return new section(inherit($item));
    }

    protected function build(): wg
    {
        return div
        (
            setClass('section-list', 'grow'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children()
        );
    }
}
