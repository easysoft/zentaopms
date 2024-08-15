<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'menu' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';

class dropdown extends wg
{
    protected static array $defineProps = array
    (
        'items?:array',
        'placement?:string',
        'strategy?:string',
        'offset?: int',
        'flip?: bool',
        'arrow?: string',
        'trigger?: string',
        'menu?: array',
        'target?: string',
        'id?: string',
        'menuClass?: string',
        'hasIcons?: bool',
        'staticMenu?: bool',
        'triggerProps?: array',
        'caret?: bool=true'
    );

    protected static array $defineBlocks = array
    (
        'trigger' => array('map' => 'btn,a'),
        'menu'    => array('map' => 'menu'),
        'items'   => array('map' => 'item')
    );

    protected function buildTrigger(bool $dynamicMenu)
    {
        list($placement, $strategy, $offset, $flip, $arrow, $trigger, $target, $id, $triggerProps, $caret) = $this->prop(array('placement', 'strategy', 'offset', 'flip', 'arrow', 'trigger', 'target', 'id', 'triggerProps', 'caret'));

        $triggerBlock = $this->block('trigger');
        if(empty($triggerBlock))        $triggerBlock = h::a($this->children());
        elseif(is_array($triggerBlock)) $triggerBlock = $triggerBlock[0];

        if(!($triggerBlock instanceof node)) return null;

        if($triggerBlock instanceof btn) $triggerBlock->setDefaultProps(array('caret' => $caret));
        $triggerOptions = array_merge(array
        (
            'placement'      => $placement,
            'strategy'       => $strategy,
            'arrow'          => $arrow,
            'flip'           => $flip,
            'offset'         => $offset,
            'target'         => $target
        ), is_array($triggerProps) ? $triggerProps : array());

        if($dynamicMenu)
        {
            $itemsList = $this->block('items');
            $items     = $this->prop('items', array());
            $menuProps = $this->prop('menu', array());

            if(!empty($itemsList))
            {
                foreach($itemsList as $item)
                {
                    if(!($item instanceof item)) continue;
                    $items[] = $item->props->toJSON();
                }
            }
            foreach($items as $index => $item)
            {
                if(!($item instanceof setting)) continue;
                $item = $item->toArray();
                $items[$index] = $item;
            }

            $menuProps = array_merge(array
            (
                'items'    => $items,
                'id'       => $id,
                'class'    => $this->prop('menuClass'),
                'hasIcons' => $this->prop('hasIcons')
            ), $menuProps);
            $triggerOptions['menu'] = array_filter_null($menuProps);
        }

        $triggerBlock->setProp('data-toggle', 'dropdown');
        $triggerBlock->setProp('data-trigger', $trigger);
        $triggerBlock->setProp('zui-toggle-dropdown', js::value(array_filter_null($triggerOptions)));
        if($id) $triggerBlock->setProp('id', "$id-toggle");

        return $triggerBlock;
    }

    protected function buildStaticMenu()
    {
        $itemsList = $this->block('items');
        $items     = $this->prop('items');
        $menuProps = $this->prop('menu');

        return new menu
        (
            setClass('dropdown-menu'),
            set::items($items),
            divorce($itemsList),
            set($menuProps)
        );
    }

    protected function buildMenu()
    {
        $menu = $this->block('menu');

        if(!$menu && $this->prop('staticMenu')) $menu = $this->buildStaticMenu();

        if(is_array($menu) && $menu) $menu = $menu[0];

        if(is_array($menu) && $menu) $menu = $menu[0];

        if($menu instanceof menu)
        {
            list($id, $menuProps, $menuClass) = $this->prop(array('id', 'menu', 'menuClass'));
            $menu->setProp('id', $id);
            $menu->setProp('class', $menuClass);
            if($menuProps) $menu->setProp($menuProps);
            if($this->hasIcons()) $menu->setProp('class', 'has-icons');
        }

        return $menu;
    }

    protected function hasIcons()
    {
        $hasIcons  = $this->prop('hasIcons');

        if($hasIcons === null)
        {
            $itemsList = $this->block('items');
            $items     = $this->prop('items');

            if(is_array($items))
            {
                foreach($items as $item)
                {
                    if((is_array($item) and isset($item['icon'])) || (($item instanceof node) && $item->hasProp('icon')))
                    {
                        $hasIcons = true;
                        break;
                    }
                }
            }
            if(!$hasIcons)
            {
                foreach($itemsList as $item)
                {
                    if(($item instanceof node) && $item->hasProp('icon'))
                    {
                        $hasIcons = true;
                        break;
                    }
                }
            }
        }

        return $hasIcons;
    }

    protected function build(): array
    {
        $menu         = $this->buildMenu();
        $triggerBlock = $this->buildTrigger(empty($menu));

        return array($triggerBlock, $menu);
    }
}
