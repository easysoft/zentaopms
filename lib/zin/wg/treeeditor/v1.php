<?php
declare(strict_types=1);
namespace zin;

class treeEditor extends wg
{
    protected static array $defineProps = array(
        'items: array',
        'type?: string',
        'id?: string',
        'icon?: string',
        'class?: string',
        'sortable?: array',
        'onSort?: function',
        'selected?: string',
        'canUpdateOrder?: bool=false',
        'canEdit?: bool=false',
        'canDelete?: bool=false',
        'canSplit?: bool=true'
    );

    protected function build(): wg
    {
        $this->setProp('items', $this->buildTree($this->prop('items')));
        $treeProps = $this->props->pick(array('items', 'activeClass', 'activeIcon', 'activeKey', 'onClickItem', 'defaultNestedShow', 'changeActiveKey', 'isDropdownMenu', 'collapsedIcon', 'expandedIcon', 'normalIcon', 'itemActions', 'hover', 'onClick', 'sortable', 'onSort'));
        $id = $this->prop('id');

        if(empty($id))
        {
            global $app;
            $id = "treeEditor-{$app->rawModule}-{$app->rawMethod}";
        }
        return div
        (
            setStyle('--menu-selected-bg', 'none'),
            zui::tree
            (
                set::_id($id),
                set::_tag('menu'),
                set::preserve($id),
                set($treeProps)
            )
        );
    }

    private function buildTree(array $items): array
    {
        global $app;

        $canEdit   = $this->prop('canEdit');
        $canDelete = $this->prop('canDelete');
        $canSplit  = $this->prop('canSplit');
        $editType  = $this->prop('type');
        $selected  = $this->prop('selected');

        foreach($items as $key => $item)
        {
            $item = (array)$item;
            if(!isset($item['content']))
            {
                if(!isset($item['text'])) $item['text'] = $item['name'];
                if(!isset($item['url']))  $item['url']  = '';

                $item['titleAttrs']['data-app'] = $app->tab;
                $item['titleAttrs']['title']    = $item['text'];

                $item['innerClass'] = 'py-0';
                $item['titleClass'] = 'text-clip';
                $item['selected']   = !empty($selected) && $selected == $item['id'];

                if(isset($item['type']) && $item['type'] == 'product')
                {
                    $item['icon'] = 'product';
                }
                else
                {
                    $item['actions'] = array();
                    $item['actions']['items'] = array();

                    if($canEdit)   $item['actions']['items'][] = array('key' => 'edit', 'icon' => 'edit', 'data-toggle' => 'modal', 'url' =>  createLink('tree', 'edit', 'moduleID=' . $item['id'] . '&type=' . $editType));
                    if($canDelete) $item['actions']['items'][] = array('key' => 'delete', 'icon' => 'trash', 'className' => 'btn ghost toolbar-item square size-sm rounded ajax-submit', 'url' => createLink('tree', 'delete', 'module=' . $item['id']));
                    if($canSplit)  $item['actions']['items'][] = array('key' => 'view',  'icon' => 'split', 'url' => $item['url'], 'data-app' => $app->tab);
                }
            }

            if(!empty($item['children']))
            {
                $item['items'] = !empty($item['children']['url']) ? $item['children'] : $this->buildTree($item['children']);
                unset($item['children']);
            }
            unset($item['type']);

            $items[$key] = $item;
        }

        return $items;
    }
}
