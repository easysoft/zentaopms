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
        $link  = $this->prop('link');
        $items = $this->prop('items');

        $menus = array();
        foreach($items as $itemKey => $item)
        {
            if(is_array($item))
            {
                $menus[] = $item;
                continue;
            }

            $menus[] = array('text' => $item, 'url' => sprintf($link, $itemKey));
        }
        return $menus;
    }

    private function getTitle()
    {
        global $lang;
        $activeKey = $this->prop('activeKey');
        if(empty($activeKey)) return $lang->product->all;

        $items = $this->prop('items');

        foreach($items as $itemID => $itemName)
        {
            if($itemID == $activeKey) return $itemName;
        }

        return $lang->product->all;
    }

    protected function build(): zui 
    {
        $items = $this->buildMenu();

        $activeKey = $this->prop('activeKey');
        $link      = $this->prop('link');
        $closeLink = str_replace('{id}', '0', $link);

        return zui::dropmenu
        (
            set('_id', 'productMenu'),
            set::className('product-menu btn'),
            set::defaultValue($activeKey),
            set::text($this->getTitle()),
            set::caret(true),
            set::popWidth(200),
            set::popClass('popup text-md'),
            set::onClick(jsRaw("(event) => {if(!event.target.closest('.is-caret')) return; openUrl('$closeLink'); return false}")),
            set::data(array('search' => false, 'checkIcon' => true, 'link' => $link, 'data' => $items)),
        );
    }
}
