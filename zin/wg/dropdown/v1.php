<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'menu' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';

class dropdown extends wg
{
    static $defineProps = 'items?:array, placement?:string, strategy?:string, offset?: number, flip?: bool, subMenuTrigger?: string, arrow?: string, trigger?: string, menuProps?: array, target?: string, id?: string';

    static $defineBlocks = array
    (
        'trigger' => array('map' => 'btn,a'),
        'menu'    => array('map' => 'menu'),
        'items'   => array('map' => 'item,actionItem')
    );

    protected function build()
    {
        list($items, $placement, $strategy, $offset, $flip, $subMenuTrigger, $arrow, $trigger, $menuProps, $target, $id) = $this->prop(array('items', 'placement', 'strategy', 'offset', 'flip', 'subMenuTrigger', 'arrow', 'trigger', 'menuProps', 'target', 'id'));

        $triggerBlock = $this->block('trigger');
        $menu         = $this->block('menu');
        $itemsList    = $this->block('items');

        if(empty($menu))
        {
            $menu = new menu
            (
                setClass('dropdown-menu'),
                set($menuProps),
                set::items($items),
                divorce($itemsList),
            );
        }
        elseif(is_array($menu))
        {
            $menu = $menu[0];
        }

        if($menu instanceof wg)
        {
            if(!empty($id)) $menu->setProp('id', $id);
            if(empty($target))
            {
                $target = $menu->id();
                if(empty($target))
                {
                    $target = $menu->gid;
                    $menu->setProp('id', $target);
                }
            }
        }

        if(empty($triggerBlock)) $triggerBlock = h::a($this->children());
        elseif(is_array($triggerBlock)) $triggerBlock = $triggerBlock[0];
        if($triggerBlock instanceof wg)
        {
            if($triggerBlock instanceof btn) $triggerBlock->setDefaultProps(array('caret' => true));
            $triggerBlock->setProp($this->props->skip(array_keys(static::getDefinedProps())));

            $triggerProps = array
            (
                'data-target'         => $triggerBlock->hasProp('target', 'href') ? NULL : "#{$target}",
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
        }

        return array($triggerBlock, $menu);
    }
}
