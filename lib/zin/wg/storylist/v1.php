<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'idlabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'simplelist' . DS . 'v1.php';

class storyList extends wg
{
    protected static array $defineProps = array
    (
        'items'        => 'array',    // 需求对象列表。
        'storyType'    => '?string',  // 需求类型。
        'onRenderItem' => '?callable' // 渲染需求对象的回调函数。
    );

    protected function getItem(object $story, bool $canView, string $storyType = 'story'): array
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
            'title'        => span(setClass('text-clip '), $story->title),
            'leading'      => idLabel::create($story->id)
        );

        if($canView)
        {
            $item['titleAttrs'] = array('data-toggle' => 'modal', 'data-size' => 'lg');
            $item['url'] = createLink('story', 'view', "id={$story->id}&version=0&param=0&storyType=$storyType");
        }

        return $item;
    }

    protected function getItems()
    {
        $stories    = $this->prop('items', array());
        $storyType  = $this->prop('storyType', 'story');
        $items      = array();
        $canView    = hasPriv($storyType, 'view', null, "storyType=$storyType");

        foreach($stories as $story)
        {
            $items[] = $this->getItem($story, $canView, $storyType);
        }

        return $items;
    }

    protected function build()
    {
        $items = $this->getItems();

        return new simpleList
        (
            setClass('story-list'),
            set::items($items),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
