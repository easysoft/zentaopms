<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'menu' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';

class dropdown extends wg
{
    static $defineProps = 'items?:array, placement?:string, strategy?:string, offset?: number, flip?: bool, subMenuTrigger?: string, arrow?: string, trigger?: string, menuProps?: array, target?: string, id?: string, menuClass?: string, hasIcons?: bool, staticMenu?: bool';

    static $defineBlocks = array
    (
        'trigger' => array('map' => 'btn,a'),
        'menu'    => array('map' => 'menu'),
        'items'   => array('map' => 'item')
    );

    protected function build()
    {
        list($items, $placement, $strategy, $offset, $flip, $subMenuTrigger, $arrow, $trigger, $menuProps, $target, $id, $menuClass, $hasIcons, $staticMenu) = $this->prop(array('items', 'placement', 'strategy', 'offset', 'flip', 'subMenuTrigger', 'arrow', 'trigger', 'menuProps', 'target', 'id', 'menuClass', 'hasIcons', 'staticMenu'));

        $triggerBlock = $this->block('trigger');
        $menu         = $this->block('menu');
        $itemsList    = $this->block('items');

        if(empty($id))     $id = $this->gid;
        if(empty($target)) $target = "#$id";

        if(empty($triggerBlock)) $triggerBlock = h::a($this->children());
        elseif(is_array($triggerBlock)) $triggerBlock = $triggerBlock[0];
        $triggerID = '';
        if($triggerBlock instanceof wg)
        {
            if($triggerBlock instanceof btn) $triggerBlock->setDefaultProps(array('caret' => true));
            $triggerBlock->setProp($this->props->skip(array_keys(static::getDefinedProps())));

            $triggerProps = array
            (
                'data-target'         => $triggerBlock->hasProp('target', 'href') ? NULL : $target,
                'data-toggle'         => 'dropdown',
                'data-placement'      => $placement,
                'data-strategy'       => $strategy,
                'data-offset'         => $offset,
                'data-flip'           => $flip,
                'data-subMenuTrigger' => $subMenuTrigger,
                'data-arrow'          => $arrow,
                'data-trigger'        => $trigger
            );
            $triggerBlock->setProp($triggerProps);

            $triggerID = $triggerBlock->id();
            if(empty($triggerID))
            {
                $triggerID = "$id-toggle";
                $triggerBlock->setProp('id', $triggerID);
            }
        }

        if(empty($menu))
        {
            if($staticMenu)
            {
                $menu = new menu
                (
                    setClass('dropdown-menu'),
                    set::items($items),
                    divorce($itemsList),
                );

                if($hasIcons === NULL)
                {
                    if(is_array($items))
                    {
                        foreach($items as $item)
                        {
                            if((is_array($item) and isset($item['icon'])) || (($item instanceof wg) && $item->hasProp('icon')))
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
                            if(($item instanceof wg) && $item->hasProp('icon'))
                            {
                                $hasIcons = true;
                                break;
                            }
                        }
                    }
                }
            }
            else
            {
                if(empty($items)) $items = array();
                if(!empty($itemsList))
                {
                    foreach($itemsList as $item)
                    {
                        if(!($item instanceof item)) continue;
                        $items[] = $item->props->toJsonData();
                    }
                }
                foreach($items as $index => $item)
                {
                    if(!isset($item['icon']) || empty($item['icon']) || str_starts_with($item['icon'], 'icon-')) continue;
                    $items[$index]['icon'] = 'icon-' . $item['icon'];
                }

                if(!is_array($menuProps)) $menuProps = array();
                $menuProps['items'] = $items;

                $menu = zui::dropdown
                (
                    set(array
                    (
                        '_to'            => "#$triggerID",
                        'trigger'        => $trigger,
                        'placement'      => $placement,
                        'strategy'       => $strategy,
                        'arrow'          => $arrow,
                        'flip'           => $flip,
                        'subMenuTrigger' => $subMenuTrigger,
                        'trigger'        => $trigger,
                        'offset'         => $offset,
                        'target'         => $target,
                        'className'      => $menuClass,
                        'hasIcons'       => $hasIcons,
                        'menu'           => $menuProps
                    ))
                );
            }
        }
        elseif(is_array($menu))
        {
            $menu = $menu[0];
        }

        if($menu instanceof menu)
        {
            $menu->setProp($menuProps);
            $menu->setProp('class', $menuClass);
            $menu->setProp('id',    $id);
            if($hasIcons) $menu->setProp('class', 'has-icons');
        }

        return array($triggerBlock, $menu);
    }
}
