<?php
declare(strict_types=1);
namespace zin;

class tree extends wg
{
    protected static array $defineProps = array(
        'items: array',
        'type?: string',
        'id?: string',
        'icon?: string',
        'class?: string',
        'canUpdateOrder?: bool=false',
        'canEdit?: bool=false',
        'canDelete?: bool=false',
        'canSplit?: bool=true',
    );

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build(): zui
    {
        $this->setProp('items', $this->buildTree($this->prop('items')));
        return zui::tree(set($this->props->pick(array('items', 'activeClass', 'activeIcon', 'activeKey', 'onClickItem', 'defaultNestedShow', 'changeActiveKey', 'isDropdownMenu', 'collapsedIcon', 'expandedIcon', 'normalIcon', 'id', 'itemActions', 'hover', 'onClick'))));
    }

    private function buildTree(array $items): array
    {
        $canUpdateOrder = $this->prop('canUpdateOrder');
        $canEdit        = $this->prop('canEdit');
        $canDelete      = $this->prop('canDelete');
        $canSplit       = $this->prop('canSplit');
        $editType       = $this->prop('type');

        foreach($items as $key => $item)
        {
            $item = (array)$item;
            $item['url']  = isset($item['url']) ? $item['url'] : '';
            $item['type'] = isset($item['type']) ? $item['type'] : '';

            $treeItem = array('text' => $item['name'], 'url' => $item['url'], 'id' => $item['id'], 'key' => $item['key']);
            if($item['type'] == 'product')
            {
                $treeItem['icon'] = 'product';
            }
            else
            {
                $treeItem['actions'] = array();
                $treeItem['actions']['items'] = array();

                if($canEdit)   $treeItem['actions']['items'][] = array('key' => 'edit',   'icon' => 'edit',  'id' => $item['id'], 'editType' => $editType, 'onClick' => jsRaw('(event, item) => window.editItem(item)'));
                if($canDelete) $treeItem['actions']['items'][] = array('key' => 'delete', 'icon' => 'trash', 'id' => $item['id'], 'class' => 'btn ghost toolbar-item square size-sm rounded ajax-submit','url' => helper::createLink('tree', 'delete', 'module=' . $item['id']));
                if($canSplit) $treeItem['actions']['items'][] = array('key' => 'view', 'icon' => 'split', 'url' => $item['url']);
            }

            if(isset($item['children'])) $treeItem['items'] = $this->buildTree($item['children']);

            $items[$key] = $treeItem;
        }

        return $items;
    }
}
