<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'content' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'panel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'entitytitle' . DS . 'v1.php';

class detailCard extends wg
{
    protected static array $defineProps = array
    (
        /* 对象类型，例如 `story`、`task` 等，如果不指定则已当前的模块名称作为对象类型。 */
        'objectType' => '?string',

        /* 对象 ID，如果不指定则尝试使用当前页面上的 `${$objectType}->id` 或者 `${$objectType}ID` 的值，例如 `$task->id` 或 `$taskID`。 */
        'objectID'   => '?int',

        /* 对象，如果不指定则尝试使用当前页面上的 `${$objectType}` 的值，例如 `$task`。 */
        'object'     => '?object',

        /* 标题，如果不指定则尝试使用当前页面上的 `${$objectType}->title` 或 `${$objectType}->name` 的值，例如 `$story->title`、`$task->name` 。 */
        'title'      => '?string',

        /* 标题颜色。 */
        'color'      => '?string',

        /* 是否在标题显示 URL。 */
        'url'        => '?bool|string',

        /* 工具栏。 */
        'toolbar'    => '?array',

        /* 详情卡片的左侧主栏目内容区域，可以通过 `-` 来指定分割线，通过键名指定标题，通过 `html()` 来指定 HTML 内容，或者指定为 `callable` 或 `Closure` 动态生成内容，或者指定为 `content()` 属性。 */
        'sections'   => '?array',

        /* 内容区域。 */
        'content'    => '?array'
    );

    protected static array $defineBlocks = array
    (
        'header'   => array(),
        'title'    => array(),
        'toolbar'  => array('map' => 'btnGroup,toolbar'),
        'body'     => array('map' => 'content,section')
    );

    public static function getPageCSS(): ?string
    {
        return <<<CSS
        CSS;
    }

    protected function created()
    {
        global $app;

        $objectType = $this->prop('objectType');
        $objectID   = $this->prop('objectID');
        $object     = $this->prop('object');

        if(!$objectType)     $objectType = $app->rawModule;
        if(!$object)         $object     = data($objectType);
        if(!$objectID)       $objectID   = $object ? $object->id : data($objectType . 'ID');

        if(!$this->hasProp('objectType'))  $this->setProp('objectType', $objectType);
        if(!$this->hasProp('objectID'))    $this->setProp('objectID',   $objectID);

        if($object)
        {
            if(!$this->hasProp('object')) $this->setProp('object', $object);
            if(!$this->hasProp('color') && isset($object->color)) $this->setProp('color', $object->color);
            if(!$this->hasProp('title')) $this->setProp('title',  isset($object->name) ? $object->name : $object->title);
        }
    }

    protected function buildToolbar()
    {
        $toolbar      = $this->prop('toolbar');
        $toolbarBlock = $this->block('toolbar');

        if(!$toolbarBlock && !$toolbar) return null;

        $toolbarProps = array_is_list($toolbar) ? array('items' => $toolbar) : $toolbar;

        return div
        (
            setClass('detail-card-toolbar panel-actions'),
            $toolbarProps ? toolbar(set::size('sm'), set($toolbarProps)) : null,
            $toolbarBlock
        );
    }

    protected function buildTitle()
    {
        list($objectID, $title, $url, $color) = $this->prop(array('objectID', 'title', 'url', 'color'));
        $titleBlock = $this->block('title');
        $titleView = $title;

        return new entityTitle
        (
            setClass('panel-title'),
            set::id($objectID),
            set::title($title),
            set::color($color),
            set::idClass('font-normal'),
            set::titleClass('text-base text-clip min-w-0 font-normal'),
            set::url($url),
            $titleBlock
        );
    }

    protected function buildHeader()
    {
        $isSimple = $this->prop('layout') === 'simple';

        return div
        (
            setClass('detail-card-header panel-heading row gap-2 items-center flex-none surface'),
            $this->buildTitle(),
            $this->block('header'),
            $this->buildToolbar()
        );
    }

    protected function buildSection(array|callable|string|setting|node $item, ?string $title = null)
    {
        if(is_callable($item))            $item = call_user_func($item, $title);
        elseif($item instanceof \Closure) $item = $item();

        if(is_null($title) && $item instanceof node) return $item;
        if($item instanceof setting) $item = $item->toArray();
        if(is_null($title) && isset($item['title']))
        {
            $title = $item['title'];
            unset($item['title']);
        }

        return div
        (
            setClass('detail-section'),
            $title ? h2(setClass('detail-section-title text-base text-gray py-1 font-normal'), $title) : null,
            div
            (
                setClass('detail-section-content py-1'),
                $item ? new content(is_array($item) ? set($item) : $item) : null,
            )
        );
    }

    protected function buildMainSections()
    {
        $sections = $this->prop('sections', array());
        $content  = $this->prop('content');
        $list     = array();

        if($content) $list[] = $this->buildSection($content, null);

        foreach($sections as $key => $item)
        {
            if($item === '-')
            {
                $list[] = hr();
                continue;
            }
            $list[] = $this->buildSection($item, is_string($key) ? $key : null);
        }

        return $list;
    }

    protected function buildBody()
    {
        return div
        (
            setClass('detail-card-body panel-body'),
            $this->buildMainSections(),
            $this->block('body')
        );
    }

    protected function build()
    {
        return div
        (
            setClass('detail-card panel rounded'),
            $this->buildHeader(),
            $this->buildBody()
        );
    }
}
