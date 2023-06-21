<?php
namespace zin;

class tree extends wg
{
    static $defineProps = array(
        'items: array',
        'id?: string',
        'class?: string',
    );

    protected function build()
    {
        $this->setProp('items', $this->buildTree($this->prop('items')));
        return zui::tree(set($this->props->pick(array('items', 'activeClass', 'activeIcon', 'activeKey', 'onClickItem', 'defaultNestedShow', 'changeActiveKey', 'isDropdownMenu'))));
    }

    private function buildTree(array $items): array
    {
        foreach($items as $key => $item)
        {
            $item = (array)$item;
            $treeItem = array('text' => $item['text'], 'url' => $item['url']);
            if(isset($item['items'])) $treeItem['items'] = $this->buildTree($item['items']);

            $items[$key] = $treeItem;
        }

        return $items;
    }
}
