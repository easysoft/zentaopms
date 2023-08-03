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
            if(!isset($item['content']))
            {
                if(!isset($item['text'])) $item['text'] = $item['name'];
                if(!isset($item['url']))  $item['url']  = '';

                if(isset($item['type']) && $item['type'] == 'product')
                {
                    $item['icon'] = 'product';
                }
                else
                {
                    $item['actions'] = array();
                    $item['actions']['items'] = array();

                    if($canEdit)   $item['actions']['items'][] = array('key' => 'edit',   'icon' => 'edit',  'id'  => $item['id'], 'editType' => $editType, 'onClick' => jsRaw('(event, item) => window.editItem(item)'));
                    if($canDelete) $item['actions']['items'][] = array('key' => 'delete', 'icon' => 'trash', 'id'  => $item['id'], 'className' => 'btn ghost toolbar-item square size-sm rounded ajax-submit','url' => helper::createLink('tree', 'delete', 'module=' . $item['id']));
                    if($canSplit)  $item['actions']['items'][] = array('key' => 'view',   'icon' => 'split', 'url' => $item['url']);
                }
            }

            if(!empty($item['children']))
            {
                $item['items'] = $this->buildTree($item['children']);
                unset($item['children']);
            }
            $items[$key] = $item;
        }

        return $items;
    }
}
