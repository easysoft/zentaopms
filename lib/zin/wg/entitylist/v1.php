<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'idlabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'simplelist' . DS . 'v1.php';

class entityList extends wg
{
    protected static array $defineProps = array
    (
        'name'         => '?string',           // 列表名称。
        'items'        => 'array',             // 对象对象列表。
        'type'         => '?string',           // 对象类型。
        'viewUrl'      => '?string|bool=true', // 查看链接模版，使用 `{id}` 代替对象 ID，如果设置为 false 不展示链接，如果设置为 true 则使用默认链接。
        'onRenderItem' => '?callable'         // 渲染对象对象的回调函数。
    );

    protected string $viewUrl = '';

    protected function created()
    {
        if(!$this->hasProp('name'))
        {
            $type = $this->prop('type');
            $this->setProp('name', $type ? "$type-list" : 'entity-list');
        }
    }

    protected function getItem(object $entity): array
    {
        $item = array
        (
            'innerClass'   => 'px-0 relative group',
            'leading'      => array(),
            'innerTag'     => 'div',
            'titleClass'   => 'flex gap-2 items-center flex-auto min-w-0',
            'textClass'    => 'flex-none',
            'actionsClass' => 'absolute top-0 right-0',
            'hint'         => $entity->title,
            'title'        => span(setClass('text-clip'), $entity->title),
            'leading'      => idLabel::create($entity->id)
        );

        if($this->viewUrl)
        {
            $item['titleAttrs'] = array('data-toggle' => 'modal', 'data-size' => 'lg');
            $item['url']        = str_replace('{id}', "$entity->id", $this->viewUrl);
        }

        return $item;
    }

    protected function getItems()
    {
        $items = $this->prop('items', array());
        $list  = array();

        foreach($items as $key => $entity)
        {
            if(is_string($entity)) $entity = (object)array('id' => $key, 'title' => $entity);
            $list[] = $this->getItem($entity);
        }

        return $list;
    }

    protected function beforeBuild()
    {
        $viewUrl = $this->prop('viewUrl');
        $type    = $this->prop('type');
        if($type)
        {
            if($viewUrl === null) $viewUrl = hasPriv($type, 'view');
            if($viewUrl === true) $viewUrl = createLink($type, 'view', 'id={id}');
        }
        $this->viewUrl = $viewUrl;
    }

    protected function build()
    {
        $this->beforeBuild();

        return new simpleList
        (
            setClass($this->prop('name')),
            set::items($this->getItems()),
            set::onRenderItem($this->prop('onRenderItem')),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
