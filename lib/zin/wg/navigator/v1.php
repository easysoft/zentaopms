<?php
declare(strict_types=1);
namespace zin;

class navigator extends wg
{
    protected static array $defineProps = array(
        'items?: array'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildSteps(): array
    {
        $items = $this->prop('items');
        if(!$items) return array();

        $steps = array();
        foreach($items as $item)
        {
            $steps[] = li
            (
                !empty($item->active) ? setClass('active') : null,
                !empty($item->url) ? set::href($item->url) : null,
                a
                (
                    setClass('btn shadow-none' . (!empty($item->active) ? ' secondary' : '')),
                    $item->text
                )
            );
        }
        return $steps;
    }

    protected function build(): wg
    {
        return ul
        (
            setID('navigator'),
            setClass('nav nav-primary'),
            $this->buildSteps()
        );
    }
}
