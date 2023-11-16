<?php
declare(strict_types=1);
namespace zin;

class productMenu extends wg
{
    protected static array $defineProps = array(
        'title?:string',
        'items?:array',
        'activeKey?:string',
        'link?:string',
        'leading?:bool=true'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function buildMenu(): array
    {
        $items = $this->prop('items');

        $menus = array();
        foreach($items as $itemKey => $item)
        {
            if(is_array($item))
            {
                $menus[] = $item;
                continue;
            }

            $menus[] = array('text' => $item, 'key' => $itemKey);
        }
        return $menus;
    }

    private function getTitle()
    {
        global $lang;
        $activeKey = $this->prop('activeKey');
        $title     = $this->prop('title');
        if(empty($activeKey) && empty($title)) return $lang->product->all;

        $items = $this->prop('items');

        foreach($items as $itemID => $itemName)
        {
            if($itemID == $activeKey) return $itemName;
        }

        return $title;
    }

    protected function build(): zui
    {
        $items = $this->buildMenu();

        $activeKey = $this->prop('activeKey');
        $link      = $this->prop('link');
        $leading   = $this->prop('leading');
        $title     = $this->getTitle();
        $closeLink = str_replace('{key}', '', $link);

        return zui::dropmenu
        (
            set('_id', 'productMenu'),
            set::_className('product-menu btn clip'),
            set::defaultValue($activeKey),
            set::text($title),
            set::caret(true),
            set::leadingAngle($leading),
            set::popWidth(200),
            set::popClass('popup text-md'),
            set::onClick(jsRaw("(event) => {if(!event.target.closest('.is-caret')) return; openUrl('$closeLink'); return false}")),
            set::data(array('search' => false, 'checkIcon' => false, 'link' => $link, 'data' => $items))
        );
    }
}
