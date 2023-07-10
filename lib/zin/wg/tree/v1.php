<?php
declare(strict_types=1);
namespace zin;

class tree extends wg
{
    protected static array $defineProps = array(
        'items: array',
        'id?: string',
        'class?: string',
    );

    protected function build()
    {
        $this->setProp('items', $this->buildTree($this->prop('items')));
        return zui::tree(set($this->props->pick(array('items', 'activeClass', 'activeIcon', 'activeKey', 'onClickItem', 'defaultNestedShow', 'changeActiveKey', 'isDropdownMenu', 'collapsedIcon', 'expandedIcon', 'normalIcon', 'id'))));
    }

    private function buildTree(array $items): array
    {
        foreach($items as $key => $item)
        {
            $item = (array)$item;
            $treeItem = array('text' => $item['name'], 'url' => $item['url'], 'id' => $item['id'], 'key' => $item['key']);
            if(isset($item['items'])) $treeItem['items'] = $this->buildTree($item['items']);

            $items[$key] = $treeItem;
        }

        return $items;
    }
}
