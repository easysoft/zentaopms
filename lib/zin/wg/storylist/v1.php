<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'idlabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'simplelist' . DS . 'v1.php';

class storyList extends wg
{
    protected static array $defineProps = array
    (
        'name'         => '?string="story-list"',  // 列表名称。
        'items'        => 'array',                 // 需求对象列表。
        'viewUrl'      => '?string|bool=true',     // 查看链接模版，使用 `{id}` 代替需求 ID，如果设置为 false 不展示链接，如果设置为 true 则使用默认链接。
        'onRenderItem' => '?callable'              // 渲染需求对象的回调函数。
    );

    protected string $viewUrl = '';

    protected function getItem(object $story): array
    {
        $item = array
        (
            'innerClass'   => 'px-0 relative group',
            'leading'      => array(),
            'innerTag'     => 'div',
            'titleClass'   => 'flex gap-2 items-center flex-auto min-w-0',
            'textClass'    => 'flex-none',
            'actionsClass' => 'absolute top-0 right-0',
            'hint'         => $story->title,
            'title'        => span(setClass('text-clip'), $story->title),
            'leading'      => idLabel::create($story->id)
        );

        if($this->viewUrl)
        {
            $item['titleAttrs'] = array('data-toggle' => 'modal', 'data-size' => 'lg');
            $item['url']        = str_replace('{id}', "$story->id", $this->viewUrl);
        }

        return $item;
    }

    protected function getItems()
    {
        $stories = $this->prop('items', array());
        $items   = array();

        foreach($stories as $story)
        {
            $items[] = $this->getItem($story);
        }

        return $items;
    }

    protected function beforeBuild()
    {
        $viewUrl = $this->prop('viewUrl');
        if($viewUrl === null) $viewUrl = hasPriv('story', 'view');
        if($viewUrl === true) $viewUrl = createLink('story', 'view', 'id={id}');
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
