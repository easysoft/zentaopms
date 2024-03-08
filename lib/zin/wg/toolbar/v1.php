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

        if($item === '-') $item = array('type' => 'divider');

        if(!($item instanceof item))
        {
            if($item instanceof node) return $item;
            $item = item(set($item));
        }

        $type = $item->prop('type');
        if($type === 'divider') return div(setClass('divider toolbar-divider'));

        $urlFormatter = $this->prop('urlFormatter');
        if($urlFormatter && ($type === 'btnGroup' || $type == 'dropdown'))
        {
            $itemChildren = $item->prop('items');
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
                $item->setProp('items', $itemChildren);
            }
        }

        if($type === 'btnGroup')                       return new btnGroup(inherit($item));
        if($type == 'dropdown' || $type == 'checkbox') return new actionItem(inherit($item));

        list($btnClass, $btnProps, $btnType, $size) = $this->prop(array('btnClass', 'btnProps', 'btnType', 'size'));
        $btn = empty($item->prop('back')) ? '\zin\btn' : '\zin\backBtn';


        $url = $item->prop('url');
        if($url && $urlFormatter)
        {
            $url = str_replace(array_keys($urlFormatter), array_values($urlFormatter), $url);
            $item->setProp('url', $url);
        }

        return new $btn
        (
            setClass('toolbar-item', $btnClass),
            set::type($btnType),
            set::size($size),
            is_array($btnProps) ? set($btnProps) : null,
            inherit($item)
        );
    }

    protected function build()
    {
        $items = $this->prop('items');
        return div
        (
            setClass('toolbar'),
            set($this->getRestProps()),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : null,
            $this->children()
        );
    }

    public static function create(array $propsOrItems, mixed ...$children): static
    {
        $props = array_is_list($propsOrItems) ? array('items' => $propsOrItems) : $propsOrItems;
        return new static(set($props), ...$children);
    }
}
