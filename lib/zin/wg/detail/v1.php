<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'backbtn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'content' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'history' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'tabs' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'tabpane' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'entitytitle' . DS . 'v1.php';

class detail extends wg
{
    protected static array $defineProps = array
    (
        /* 布局，如果为 `simple` 则适用于对话框。 */
        'layout'     => '?string',

        /* 返回按钮，可以为：`true` 显示默认的返回按钮，`false` 不显示返回按钮，或者指定返回按钮 backBtn 的 back 属性，或者通过数组指定 backBtn 所有属性。 */
        'backBtn'    => '?bool|string|array=true',

        /* 对象类型，例如 `story`、`task` 等，如果不指定则已当前的模块名称作为对象类型。 */
        'objectType' => '?string',

        /* 对象 ID，如果不指定则尝试使用当前页面上的 `${$objectType}->id` 或者 `${$objectType}ID` 的值，例如 `$task->id` 或 `$taskID`。 */
        'objectID'   => '?int',

        /* 对象标题颜色。 */
        'color'      => '?string',

        /* 对象，如果不指定则尝试使用当前页面上的 `${$objectType}` 的值，例如 `$task`。 */
        'object'     => '?object',

        /* 是否已删除。 */
        'deleted?: bool',

        /* 父级对象。 */
        'parent'     => '?object',

        /* 父级对象类型。 */
        'parentType' => '?string',

        /* 父级对象 ID。 */
        'parentID'   => '?int|string',

        /* 父级标题。 */
        'parentTitle' => '?string',

        /* 父级标题链接。 */
        'parentUrl'  => '?string',

        /* 标题，如果不指定则尝试使用当前页面上的 `${$objectType}->title` 或 `${$objectType}->name` 的值，例如 `$story->title`、`$task->name` 。 */
        'title'      => '?string',

        /* 底部固定操作按钮的定义，不包括返回按钮，可以通过 `-` 来指定分割线。 */
        'actions'    => '?array',

        /* 右上方工具栏的定义。 */
        'toolbar'    => '?array',

        /* 详情页的左侧主栏目内容区域，可以通过 `-` 来指定分割线，通过键名指定标题，通过 `html()` 来指定 HTML 内容，或者指定为 `callable` 或 `Closure` 动态生成内容，或者指定为 `content()` 属性。 */
        'sections'   => '?array',

        /* 详情页的右侧侧边栏标签页区域，可以通过 `-` 来指定分割线，通过键名指定标题，通过 `html()` 来指定 HTML 内容，或者指定为 `callable` 或 `Closure` 动态生成内容，或者指定为 `content()` 属性。 */
        'tabs'       => '?array',

        /* 详情页的右侧侧边栏宽度，如果不指定则默认为 `370`。 */
        'sideWidth'  => '?int=370',

        /* 详情页的左侧主栏目历史记录，如果设定为 `true` 显示当前对象默认的理智记录，否如果设置为 `false` 不显示历史记录，如果设置为数组则作为 `history()` 部件的属性来创建历史记录。 */
        'history'    => '?array|bool=true',

        /* 详情链接生成模版，例如 `/m=story&f=view&storyID={id}`，如果不指定则自动根据当前模块名和方法名生成。 */
        'linkCreator' => '?string',

        /* 上一个对象按钮链接，也可以通过数组指定按钮的所有属性，如果指定为 true，则自动从 $preAndNext 对象上获取 ID 生成链接。 */
        'prevBtn'    => '?string|array|bool=true',

        /* 下一个对象按钮链接，也可以通过数组指定按钮的所有属性，如果指定为 true，则自动从 $preAndNext 对象上获取 ID 生成链接。 */
        'nextBtn'    => '?string|array|bool=true'
    );

    protected static array $defineBlocks = array
    (
        'header'   => array(),
        'title'    => array(),
        'main'     => array('map' => 'content,section'),
        'side'     => array('map' => 'tabs'),
        'actions'  => array('map' => 'btn'),
        'toolbar'  => array('map' => 'btnGroup,toolbar')
    );

    public static function getPageCSS(): ?string
    {
        return <<<'CSS'
        .detail-main > * + * {margin-top: 16px}
        .detail-section.panel {--tw-ring-opacity: 0}
        .detail-section.panel .panel-heading {padding: 4px 0; margin-bottom: 8px}
        .detail-section.panel .panel-heading .listitem {padding: 0}
        .detail-section.panel .panel-body {padding: 4px 1px}
        .detail-section-title, .detail-section.panel .panel-heading {background: var(--color-canvas); position: sticky; top: -16px; z-index: 1}
        .detail-side {scrollbar-gutter: stable;}
        .detail-side > * + * {border-top: 1px solid var(--color-border)}
        .detail-side .tabs {padding: 12px 8px 12px 16px}
        .detail-side .tabs-header {position: sticky; top: 0; background: var(--color-surface-light);}
        .detail-side .tab-pane {padding: 0}
        .detail-side .tabs .nav-tabs {width: fit-content; border-bottom: 1px solid var(--color-border)}
        CSS;
    }

    protected function created()
    {
        global $app;

        $layout     = $this->prop('layout');
        $objectType = $this->prop('objectType');
        $objectID   = $this->prop('objectID');
        $object     = $this->prop('object');

        if(is_null($layout))
        {
            $layout = isInModal() ? 'simple' : 'default';
            $this->setProp('layout', $layout);
        }

        if(!$objectType)     $objectType = $app->rawModule;
        if(!$object)         $object     = data($objectType);
        if(!$objectID)       $objectID   = $object ? $object->id : data($objectType . 'ID');

        if(!$objectType || !$objectID || !$object)
        {
            $this->triggerError('The objectType, objectID or object property of widget "detail" is undefined.');
        }

        if(!$this->prop('objectType')) $this->setProp('objectType', $objectType);
        if(!$this->prop('objectID'))   $this->setProp('objectID',   $objectID);
        if(!$this->prop('object'))     $this->setProp('object',     $object);

        if($object)
        {
            if(!$this->hasProp('title')) $this->setProp('title', isset($object->name) ? $object->name : $object->title);
            if(!$this->hasProp('color') && isset($object->color)) $this->setProp('color', $object->color);
            if(!$this->hasProp('deleted') && isset($object->deleted)) $this->setProp('deleted', $object->deleted);
        }

        $parent = $this->prop('parent');
        if(!$parent && $object && isset($object->parent) && is_object($object->parent))
        {
            $parent = $object->parent;
            $this->setProp('parent', $parent);
        }
        if($parent)
        {
            if(!$this->hasProp('parentID'))    $this->setProp('parentID',    $parent->id);
            if(!$this->hasProp('parentTitle')) $this->setProp('parentTitle', isset($parent->name) ? $parent->name : $parent->title);
            if(!$this->hasProp('parentUrl'))   $this->setProp('parentUrl',   $parent->url);
        }
    }

    protected function buildBackBtn(?array $props = null)
    {
        global $lang;

        $backBtn = $this->prop('backBtn');
        if($backBtn === false) return null;

        if(is_string($backBtn))     $backBtn = array('back' => $backBtn);
        elseif(!is_array($backBtn)) $backBtn = array();

        if($props) $backBtn = array_merge($backBtn, $props);
        return new backBtn
        (
            set::icon('back'),
            set::text($lang->goback),
            set($backBtn)
        );
    }

    protected function buildTitle()
    {
        list($object, $objectID, $title, $color, $objectType, $parent, $parentID, $parentUrl, $parentTitle, $parentType) = $this->prop(array('object', 'objectID', 'title', 'color', 'objectType', 'parent', 'parentID', 'parentUrl', 'parentTitle', 'parentType'));
        $titleBlock = $this->block('title');

        return new entityTitle
        (
            setClass('min-w-0'),
            set::id($objectID),
            set::object($object),
            set::title($title),
            set::titleClass('text-lg text-clip font-bold'),
            set::type($objectType),
            set::color($color),
            set::parent($parent),
            set::parentID($parentID),
            set::parentUrl($parentUrl),
            set::parentTitle($parentTitle),
            set::parentType($parentType),
            $titleBlock
        );
    }

    protected function buildToolbar()
    {
        $toolbar      = $this->prop('toolbar');
        $toolbarBlock = $this->block('toolbar');

        if(!$toolbarBlock && !$toolbar) return null;

        $toolbarProps = array_is_list($toolbar) ? array('items' => $toolbar) : $toolbar;

        return div
        (
            setClass('detail-toolbar'),
            $toolbarProps ? toolbar(set($toolbarProps)) : null,
            $toolbarBlock
        );
    }

    protected function buildHeader()
    {
        $isSimple = $this->prop('layout') === 'simple';

        return div
        (
            setClass('detail-header row gap-2 items-center flex-none'),
            $isSimple ? null : $this->buildBackBtn(array('type' => 'primary-outline', 'class' => 'mr-2 size-md')),
            $this->buildTitle(),
            $this->block('header'),
            div(setClass('flex-auto')),
            $isSimple ? null : $this->buildToolbar()
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
            $title ? h2(setClass('detail-section-title text-md py-1'), $title) : null,
            div
            (
                setClass('detail-section-content py-1'),
                $item ? new content(is_array($item) ? set($item) : $item) : null,
            )
        );
    }

    protected function buildMainSections()
    {
        $sections = $this->prop('sections');
        $list     = array();

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

    protected function buildHistory()
    {
        $history = $this->prop('history');
        if($history === false) return null;

        if(!is_array($history)) $history = array();

        return new history
        (
            set::class('detail-section overflow-visible'),
            set::panel(false),
            set::objectType($this->prop('objectType')),
            set::objectID($this->prop('objectID')),
            set($history)
        );
    }

    protected function buildActions()
    {
        $actions      = $this->prop('actions');
        $objectID     = $this->prop('objectID');
        $actionsBlock = $this->block('actions');
        $isSimple     = $this->prop('layout') === 'simple';

        if(!$actionsBlock && !is_array($actions)) return null;

        $toolbarProps = array_is_list($actions) ? array('items' => $actions) : $actions;
        if(!$isSimple)
        {
            $backBtn = $this->buildBackBtn(array('type' => 'ghost'));
            if(empty($toolbarProps['items'])) $toolbarProps['items'] = array($backBtn);
            else array_unshift($toolbarProps['items'], $backBtn, array('type' => 'divider'));
        }

        foreach($toolbarProps['items'] as &$item)
        {
            if(is_array($item) && isset($item['url'])) $item['url'] = str_replace('{id}', "$objectID", $item['url']);
        }

        return div
        (
            setClass('detail-actions center sticky bottom-0 z-10'),
            div
            (
                setClass('bg-black text-fore-in-dark backdrop-blur bg-opacity-60 rounded p-1.5'),
                $toolbarProps ? toolbar
                (
                    set::btnType('ghost'),
                    set($toolbarProps)
                ) : null,
                $actionsBlock
            )
        );
    }

    protected function buildMain()
    {
        $isSimple = $this->prop('layout') === 'simple';

        return div
        (
            setClass('detail-main flex-auto w-full scrollbar-hover scrollbar-thin overflow-auto px-6 py-4'),
            $this->buildMainSections(),
            $this->block('main'),
            $this->buildHistory(),
            $isSimple ? null : $this->buildActions()
        );
    }

    protected function buildTab($tab)
    {
        $title = isset($tab['title']) ? $tab['title'] : null;
        unset($tab['title']);

        return new tabPane
        (
            set::title($title),
            new content(set($tab))
        );
    }

    protected function buildTabs($group, $tabs)
    {
        $tabsView = new tabs
        (
            setData('group', $group),
            set::collapse(true)
        );
        foreach($tabs as $tab)
        {
            $tabsView->add($this->buildTab($tab));
        }
        return $tabsView;
    }

    protected function buildTabsList()
    {
        $tabs = $this->prop('tabs');
        if(!$tabs) return null;

        $groups = array();
        foreach($tabs as $item)
        {
            $item = toArray($item);
            $group = isset($item['group']) ? $item['group'] : '';
            unset($item['group']);
            if(isset($groups[$group])) $groups[$group][] = $item;
            else                       $groups[$group] = array($item);
        }

        $views = array();
        foreach($groups as $groupName => $items)
        {
            $views[] = $this->buildTabs($groupName, $items);
        }
        return $views;
    }

    protected function buildSide()
    {
        return div
        (
            setClass('detail-side flex-none surface-light border-l scrollbar-hover scrollbar-thin overflow-auto min-h-0'),
            setStyle('width', $this->prop('sideWidth') . 'px'),
            $this->buildTabsList(),
            $this->block('side')
        );
    }

    protected function buildBody()
    {
        return div
        (
            setClass('detail-body rounded shadow row items-stretch flex-auto canvas min-h-0'),
            $this->buildMain(),
            $this->buildSide()
        );
    }

    protected function buildPrevAndNext()
    {
        list($linkCreator, $prevBtn, $nextBtn) = $this->prop(array('linkCreator', 'prevBtn', 'nextBtn'));
        $preAndNext = data('preAndNext');

        if(!$linkCreator && $preAndNext && ($prevBtn === true || $nextBtn === true))
        {
            global $app;
            $objectType  = $this->prop('objectType');
            $linkCreator = createLink($objectType, $app->rawMethod, $objectType . 'ID={id}');
        }
        if($prevBtn === true && $preAndNext && $preAndNext->pre && $linkCreator)
        {
            $prevBtn = array();
            $prevBtn['url']  = str_replace('{id}', "{$preAndNext->pre->id}", $linkCreator);
            $prevBtn['hint'] = "#{$preAndNext->pre->id} " . (isset($preAndNext->pre->title) ? $preAndNext->pre->title : $preAndNext->pre->name);
        }
        elseif(is_string($prevBtn))
        {
            $prevBtn = array('url' => $prevBtn);
        }
        if($nextBtn === true && $preAndNext && $preAndNext->next && $linkCreator)
        {
            $nextBtn = array();
            $nextBtn['url']  = str_replace('{id}', "{$preAndNext->next->id}", $linkCreator);
            $nextBtn['hint'] = "#{$preAndNext->next->id} " . (isset($preAndNext->next->title) ? $preAndNext->next->title : $preAndNext->next->name);
        }
        elseif(is_string($nextBtn))
        {
            $nextBtn = array('url' => $nextBtn);
        }

        $buttons = array();
        if(is_array($prevBtn))
        {
            $buttons[] = new btn
            (
                setClass('detail-prev-btn absolute top-0 left-0 inverse rounded-full w-12 h-12 center bg-opacity-40 backdrop-blur ring-0'),
                set::icon('angle-left icon-2x text-canvas'),
                set($prevBtn)
            );
        }
        if(is_array($nextBtn))
        {
            $buttons[] = new btn
            (
                setClass('detail-next-btn absolute top-0 right-0 inverse rounded-full w-12 h-12 center bg-opacity-40 backdrop-blur ring-0'),
                set::icon('angle-right icon-2x text-canvas'),
                set($nextBtn)
            );
        }

        if(!$buttons) return null;
        return div
        (
            setClass('detail-prev-next absolute right-0 left-0 z-10'),
            setStyle(array('top' => '50%', 'margin' => '-24px -16px auto')),
            $buttons
        );
    }

    protected function build()
    {
        list($objectType, $objectID) = $this->prop(array('objectType', 'objectID'));
        $isSimple = $this->prop('layout') === 'simple';

        return div
        (
            setClass('detail-view col relative gap-2.5', "detail-$objectType-$objectID"),
            $isSimple ? null : setStyle('height', 'calc(100vh - 61px)'),
            $this->buildHeader(),
            $this->buildBody(),
            $this->buildPrevAndNext()
        );
    }
}
