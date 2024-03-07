<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'idlabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'statuslabel' . DS . 'v1.php';

class relatedList extends wg
{
    protected static array $defineProps = array
    (
        'data'         => '?array',     // 要显示的类型数据定义。
        'showCount'    => '?bool=true', // 是否显示数量。
        'onRenderItem' => '?callback'   // 渲染需求对象的回调函数。
    );

    protected function getCommonItem(string $type, array $group, object $item): array
    {
        $title = isset($item->title) ? $item->title : $item->name;
        $info = array
        (
            'title'   => $title,
            'hint'    => $title,
            'leading' => array('html' => wg(idLabel::create($item->id))->render())
        );

        $urlTemplate = isset($group['url']) ? $group['url'] : null;
        if(is_null($urlTemplate)) $urlTemplate = common::hasPriv($type, 'view') ? createLink($type, 'view', "{$type}ID={id}") : null;
        $url = is_string($urlTemplate) ? str_replace('{id}', "$item->id", $urlTemplate) : null;
        if($url) $info['url'] = $url;

        if(isset($group['statusList']) && isset($item->status)) $info['content'] = array('html' => wg(statusLabel::create($item->status, $group['statusList'][$item->status]))->render(), 'className' => 'flex-none');

        $props = isset($group['props']) ? $group['props'] : array('data-toggle' => 'modal', 'data-size' => 'lg');
        if($props) $info = array_merge($info, $props);

        return $info;
    }

    protected function getGroupItems(string $type, array $group): array
    {
        $items = isset($group['items']) ? $group['items'] : array();
        if(is_object($items)) $items = array($items);

        if(!$items) return array();

        $list         = array();
        $methodName   = 'get'. ucfirst($type) . 'Item';
        $onRenderItem = $this->prop('onRenderItem');

        foreach($items as $index => $item)
        {
            if(is_string($item)) $item = (object)array('id' => $index, 'title' => $item);

            $listItem = method_exists($this, $methodName) ? $this->$methodName($group, $item) : $this->getCommonItem($type, $group, $item);

            if(is_callable($onRenderItem)) $listItem = $onRenderItem($listItem, $item, $type, $group);

            if(isset($group['onRender'])) $listItem = $group['onRender']($listItem, $item);
            $list[] = $listItem;
        }

        return $list;
    }

    protected function getItems(): array
    {
        global $lang, $app;

        $data       = $this->prop('data', array());
        $showCount  = $this->prop('showCount');
        $items      = array();
        $moduleName = $app->rawModule;

        foreach($data as $type => $group)
        {
            if(isset($group['title']))
            {
                $title = $group['title'];
            }
            else
            {
                $langName = 'legend' . ucfirst($type);
                $title    = isset($lang->$moduleName->$langName) ? $lang->$moduleName->$langName : $type;
            }

            $groupItems = $this->getGroupItems($type, $group);

            $items[] = array
            (
                'title'   => $title,
                'items'   => $groupItems,
                'content' => $showCount ? array('html' => '<span class="label gray-pale rounded-full size-sm">' . count($groupItems) . '</span>') : null
            );
        }

        return $items;
    }

    protected function build()
    {
        return zui::nestedList
        (
            set::className('story-related-list'),
            set::itemProps(array('titleClass' => 'text-clip')),
            set::items($this->getItems())
        );
    }
}
