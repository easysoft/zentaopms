<?php
namespace zin;

class productMenu extends wg
{
    protected static $defineProps = array(
        'title?:string',
        'items?:array',
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function build()
    {
        $title     = $this->prop('title');
        $items     = $this->prop('items');

        return dropdown
        (
            to
            (
                'trigger',
                div
                (
                    setClass('program-menu'),
                    h::header
                    (
                        div
                        (
                            setClass('title-container'),
                            div
                            (
                                setClass('icon-container down'),
                                h::i(setClass('gg-chevron-down')),
                            ),
                            span($title)
                        ),
                    )
                )
            ),
            to
            (
                'menu', menu
                (
                    set::class('dropdown-menu'),
                    set::items($items)
                )
            ),
        );
    }
}
