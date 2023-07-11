<?php
declare(strict_types=1);
namespace zin;

class productMenu extends wg
{
    protected static array $defineProps = array(
        'title?:string',
        'items?:array',
        'activeKey?:string',
        'link?:string'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function buildMenu(): array
    {
        $link      = $this->prop('link');
        $items     = $this->prop('items');
        $activeKey = $this->prop('activeKey');

        $menus = array();
        foreach($items as $itemKey => $item)
        {
            if(is_array($item))
            {
                $menus[] = $item;
                continue;
            }

            $menus[] = array('text' => $item, 'active' => $itemKey == $activeKey, 'url' => sprintf($link, $itemKey));
        }
        return $menus;
    }

    protected function build(): wg
    {
        $title = $this->prop('title');
        $items = $this->buildMenu();

        return div
        (
            setClass('program-menu'),
            set('data-zin-id', $this->gid),
            h::header
            (
                set('data-toggle', 'dropdown'),
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
            ),
            menu
            (
                set::class('dropdown-menu'),
                set::items($items)
            )
        );
    }
}
