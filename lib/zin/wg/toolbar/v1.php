<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'backbtn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'actionitem' . DS . 'v1.php';

class toolbar extends wg
{
    protected static array $defineProps = array
    (
        'items?: array',
        'btnClass?: string',
        'btnType?: string',
        'size?: string',
        'btnProps?: array',
        'urlFormatter?: array'
    );

    public function onBuildItem($item): node|null
    {
        if($item === null) return null;

        if(!($item instanceof item))
        {
            if($item instanceof node) return $item;
            $item = item(set($item));
        }

        $type = $item->prop('type');
        if($type === 'divider')                        return div(setClass('divider toolbar-divider'));
        if($type === 'btnGroup')                       return new btnGroup(inherit($item));
        if($type == 'dropdown' || $type == 'checkbox') return new actionItem(inherit($item));

        list($btnClass, $btnProps, $btnType, $size) = $this->prop(array('btnClass', 'btnProps', 'btnType', 'size'));
        $btn = empty($item->prop('back')) ? '\zin\btn' : '\zin\backBtn';

        return new $btn
        (
            setClass('toolbar-item', $btnClass),
            set::type($btnType),
            set::size($size),
            is_array($btnProps) ? set($btnProps) : null,
            inherit($item)
        );
    }

    protected function buildItems()
    {
        $items = $this->prop('items');
        if(!$items) return null;

        $urlFormatter = $this->prop('urlFormatter');
        $itemGroups   = array();

        foreach($items as $item)
        {
            if($item === '-') $item = array('type' => 'divider');
            $group = null;
            if(is_array($item) && isset($item['group']))
            {
                $group = $item['group'];
                unset($item['group']);
            }
            if(is_null($group)) $group = count($itemGroups) ? array_keys($itemGroups)[0] : '';

            if($urlFormatter && is_array($item))
            {
                $url = isset($item['url']) ? $item['url'] : null;
                if($url)
                {
                    $url = str_replace(array_keys($urlFormatter), array_values($urlFormatter), $url);
                    $item['url'] = $url;
                }
                $itemChildren = isset($item['items']) ? $item['items'] : null;
                if(is_array($itemChildren))
                {
                    foreach($itemChildren as $key => &$child)
                    {
                        if(is_array($child) && isset($child['url']))
                        {
                            $url = $child['url'];
                            if($url)
                            {
                                $url = str_replace(array_keys($urlFormatter), array_values($urlFormatter), $url);
                                $itemChildren[$key]['url'] = $url;
                            }
                        }
                    }
                    $item['items'] = $itemChildren;
                }
            }

            if(!isset($itemGroups[$group])) $itemGroups[$group] = array();
            $itemGroups[$group][] = $item;
        }

        $list = array();
        foreach($itemGroups as $group => $items)
        {
            if(count($list)) $list[] = div(setClass('divider toolbar-divider'));
            foreach($items as $item)
            {
                $list[] = $this->onBuildItem($item);
            }
        }
        return $list;
    }

    protected function build()
    {
        return div
        (
            setClass('toolbar'),
            set($this->getRestProps()),
            $this->buildItems(),
            $this->children()
        );
    }

    public static function create(array $propsOrItems, mixed ...$children): static
    {
        $props = array_is_list($propsOrItems) ? array('items' => $propsOrItems) : $propsOrItems;
        return new static(set($props), ...$children);
    }
}
