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
require_once dirname(__DIR__) . DS . 'toolbar' . DS . 'v1.php';

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
        'parentID'   => '?int|string=false',

        /* 父级标题。 */
        'parentTitle' => '?string',

        /* 父级标题链接。 */
        'parentUrl'  => '?string',

        /* 父级标题属性。 */
        'parentTitleProps'  => '?array',

        /* 标题，如果不指定则尝试使用当前页面上的 `${$objectType}->title` 或 `${$objectType}->name` 的值，例如 `$story->title`、`$task->name` 。 */
        'title'      => '?string',

        /* 底部固定操作按钮的定义，不包括返回按钮，可以通过 `-` 来指定分割线，如果没有指定 actions 属性，则从 actionList 和 operateList 生成。 */
        'actions'    => '?array',

        /* 对象类型可用操作配置，如果没有指定 actionList 属性，则从 $config->$objectType->actionList 获取。 */
        'actionList' => '?array',

        /* 对象类型当前操作配置，如果没有指定 operateList 属性，则从 $config->$objectType->$methoedName->actionList 获取。 */
        'operateList' => '?array',

        /* 操作按钮链接格式化参数。 */
        'urlFormatter' => '?array',

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
        'sections' => array(),
        'side'     => array('map' => 'tabs'),
        'actions'  => array('map' => 'btn'),
        'toolbar'  => array('map' => 'btnGroup,toolbar')
    );

    public static function getPageCSS(): ?string
    {
        return <<<'CSS'
        .detail-sections > * + * {margin-top: 16px}
        .detail-section.panel {--tw-ring-opacity: 0}
        .detail-section-title, .detail-section.panel .panel-heading {background: var(--color-canvas); position: sticky; top: 0; z-index: 2}
        .detail-section.panel .panel-heading {z-index: 1}
        .detail-section .detail-section .detail-section-title {z-index: 0}
        .detail-side > * + * {margin-top: 8px}
        .detail-side .tabs {padding: 12px 8px 12px 16px}
        .detail-side .tabs-header {position: sticky; top: 0;}
        .modal-dialog .detail-side .tabs-header {position: relative}
        .detail-side .tab-pane {padding: 0}
        .detail-sections .history-panel > .panel-heading,
        .detail-sections .history-panel > .panel-body {padding: 0.75rem 1.5rem}
        .detail-sections .history-panel-header > .listitem {padding: 0}
        .important-w-0 {width: 0!important;}
        .no-width {padding: 0!important; width: 0!important; overflow: hidden!important;}
        .detail-toggle {margin: 0!important;}
        .detail-toggle:hover {background-color: rgba(var(--color-border-rgb), var(--tw-bg-opacity)); transition-duration: .15s; transition-property: background-color; transition-timing-function: cubic-bezier(.4,0,.2,1);}
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

        if(!$this->hasProp('urlFormatter')) $this->setProp('urlFormatter', array('{id}' => $objectID));
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
            set::hint($lang->goback),
            setKey('backBtn'),
            set($backBtn)
        );
    }

    protected function buildTitle()
    {
        list($object, $objectID, $title, $color, $objectType, $parent, $parentID, $parentUrl, $parentTitle, $parentType, $parentTitleProps) = $this->prop(array('object', 'objectID', 'title', 'color', 'objectType', 'parent', 'parentID', 'parentUrl', 'parentTitle', 'parentType', 'parentTitleProps'));
        $titleBlock   = $this->block('title');
        $titleLeading = $this->block('titleLeading');

        return new entityTitle
        (
            setClass('min-w-0'),
            setKey('title'),
            set::id($objectID),
            set::object($object),
            set::title($title),
            set::titleClass('text-lg text-clip font-bold'),
            set::titleProps(array('title' => $title)),
            set::type($objectType),
            set::color($color),
            set::parentTitleClass('text-lg text-clip font-bold'),
            set::parent($parent),
            set::parentID($parentID),
            set::parentUrl($parentUrl),
            set::parentTitle($parentTitle),
            set::parentType($parentType),
            set::parentTitleProps($parentTitleProps),
            set::joinerClass('text-lg'),
            $titleLeading ? to::leading($titleLeading) : null,
            $titleBlock
        );
    }

    protected function buildToolbar()
    {
        $toolbar      = $this->prop('toolbar');
        $toolbarBlock = $this->block('toolbar');

        if(!$toolbarBlock && !$toolbar) return null;

        return div
        (
            setClass('detail-toolbar'),
            setKey('toolbar'),
            $toolbar ? toolbar::create($toolbar, set::urlFormatter($this->prop('urlFormatter'))) : null,
            $toolbarBlock
        );
    }

    protected function buildHeader()
    {
        $isSimple = $this->prop('layout') === 'simple';

        return div
        (
            setClass('detail-header row gap-2 items-center flex-none'),
            setKey('header'),
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

        $titleActions = isset($item['titleActions']) ? $item['titleActions'] : null;
        if($titleActions) unset($item['titleActions']);

        return div
        (
            setClass('detail-section'),
            setKey($title),
            $title ? div
            (
                setClass('detail-section-title row items-center gap-2'),
                span(setClass('text-md py-1 font-bold'), $title),
                $titleActions ? toolbar::create($titleActions) : null
            ) : null,
            div
            (
                setClass('detail-section-content py-1'),
                $item ? new content(is_array($item) ? set($item) : $item) : null,
            )
        );
    }

    protected function buildMainSections()
    {
        global $app, $config;
        $sections = $this->prop('sections');
        if($config->edition != 'open' && empty($app->installing) && empty($app->upgrading)) $sections = $app->control->loadModel('flow')->buildExtendZinValue($sections, $this->prop('object'), 'info');

        $list = array();
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

        return div
        (
            setClass('detail-sections canvas shadow rounded'),
            setKey('historyWrapper'),
            new history
            (
                set::className('detail-section overflow-visible'),
                set::objectType($this->prop('objectType')),
                set::objectID($this->prop('objectID')),
                set($history)
            )
        );
    }

    protected function buildActions()
    {
        $actions      = $this->prop('actions');
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
        if(empty($toolbarProps['items']) && empty($actions)) return null;

        return div
        (
            setClass('detail-actions center sticky mt-4 bottom-4 z-10'),
            setKey('actions'),
            div
            (
                setClass('bg-black text-fore-in-dark backdrop-blur bg-opacity-60 rounded p-1.5'),
                $toolbarProps ? toolbar
                (
                    setClass('no-morph'),
                    set::urlFormatter($this->prop('urlFormatter')),
                    set::btnType('ghost'),
                    is_array($toolbarProps) ? set($toolbarProps) : null
                ) : null,
                $actionsBlock
            )
        );
    }

    protected function buildMain()
    {
        return div
        (
            setClass('detail-main flex-auto col gap-2 min-w-0'),
            div
            (
                setClass('detail-sections canvas shadow rounded px-6 py-4'),
                setKey('main'),
                $this->buildMainSections(),
                $this->block('main')
            ),
            $this->block('sections'),
            $this->children(),
            $this->buildHistory(),
            $this->buildActions()
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
            setClass('canvas rounded shadow'),
            setData('group', $group),
            setKey($group),
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
        global $app, $config;
        $tabs = $this->prop('tabs');
        if($config->edition != 'open' && empty($app->installing) && empty($app->upgrading)) $tabs = $app->control->loadModel('flow')->buildExtendZinValue($tabs, $this->prop('object'), 'basic');
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
            setClass('detail-side flex-none relative'),
            setStyle('width', $this->prop('sideWidth') . 'px'),
            setKey('side'),
            $this->buildTabsList(),
            $this->block('side'),
            div(
                setClass('detail-toggle h-full w-2 absolute top-0 flex justify-center items-center'),
                setStyle('left', '-.5rem'),
                btn(
                    setClass('w-4 rounded-lg'),
                    set::icon('chevron-right'),
                    set::iconClass('text-sm text-gray'),
                    on::click()
                        ->do(<<<'JS'
                            $('.detail-side').toggleClass('important-w-0');
                            $('.tabs').toggleClass('no-width');
                            $element.find('.icon')
                                .toggleClass('icon-chevron-right')
                                .toggleClass('icon-chevron-left');
                        JS)
                )
            )
        );
    }

    protected function buildBody()
    {
        return div
        (
            setClass('detail-body row gap-2 items-start'),
            setKey('body'),
            $this->buildMain(),
            $this->buildSide()
        );
    }

    protected function buildPrevAndNext()
    {
        if($this->prop('layout') === 'simple') return null;

        list($linkCreator, $prevBtn, $nextBtn, $objectType) = $this->prop(array('linkCreator', 'prevBtn', 'nextBtn', 'objectType'));
        $preAndNext = data('preAndNext');
        $idKey      = isset($preAndNext->idKey) ? $preAndNext->idKey : 'id';

        global $app;
        if(!$linkCreator && $preAndNext && ($prevBtn === true || $nextBtn === true))
        {
            $linkCreator = createLink($app->rawModule, $app->rawMethod, $objectType . 'ID={id}');
        }
        if($prevBtn === true && $preAndNext && $preAndNext->pre && $linkCreator)
        {
            $prevBtn  = array();
            $objectID = $preAndNext->pre->$idKey;
            $prevBtn['url']  = str_replace('{id}', "{$objectID}", $linkCreator);
            $prevBtn['hint'] = "#{$objectID} " . (isset($preAndNext->pre->title) ? $preAndNext->pre->title : $preAndNext->pre->name);
        }
        elseif(is_string($prevBtn))
        {
            $prevBtn = array('url' => $prevBtn);
        }
        if($nextBtn === true && $preAndNext && $preAndNext->next && $linkCreator)
        {
            $nextBtn  = array();
            $objectID = $preAndNext->next->$idKey;
            $nextBtn['url']  = str_replace('{id}', "{$objectID}", $linkCreator);
            $nextBtn['hint'] = "#{$objectID} " . (isset($preAndNext->next->title) ? $preAndNext->next->title : $preAndNext->next->name);
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
                setData('app', $app->tab),
                set($prevBtn)
            );
        }
        if(is_array($nextBtn))
        {
            $buttons[] = new btn
            (
                setClass('detail-next-btn absolute top-0 right-0 inverse rounded-full w-12 h-12 center bg-opacity-40 backdrop-blur ring-0'),
                set::icon('angle-right icon-2x text-canvas'),
                setData('app', $app->tab),
                set($nextBtn)
            );
        }

        if(!$buttons) return null;
        return div
        (
            setClass('detail-prev-next fixed top-0 left-0 bottom-0 right-0 z-10 pointer-events-none'),
            div
            (
                setClass('container relative pointer-events-auto'),
                setStyle(array('top' => '50%', 'margin' => '-24px auto auto')),
                $buttons
            )
        );
    }

    protected function build()
    {
        global $app;
        list($objectType, $objectID, $layout) = $this->prop(array('objectType', 'objectID', 'layout'));

        return div
        (
            setClass('detail-view col relative gap-2.5'),
            setData('id', $objectID),
            setData('type', $objectType),
            $this->buildHeader(),
            $this->buildBody(),
            $layout === 'simple' ? null : $this->buildPrevAndNext(),
            html($app->control->appendExtendCssAndJS('', '', $this->prop('object'))),
            js
            (
                'const $doc = $(document)',
                'const binddedKey = "zt.detail.bindded"',
                'if($doc.data(binddedKey)) return',
                '$doc.on("keyup.detail.zt", e => {',
                    'if($(".modal.show").length) return',
                    'const $target = $(e.target);',
                    'if($target.is("input,textarea,select,[contenteditable]")) return',
                    'let $btn = null',
                    'if(e.keyCode === 37) $btn = $(".detail-prev-btn")',
                    'else if(e.keyCode === 39) $btn = $(".detail-next-btn")',
                    'if($btn && $btn.length) $btn[0].click()',
                '}).data(binddedKey, true)',
                '$doc.one("pageunmount.app", () => {$(document).off("keyup.detail.zt").removeData(binddedKey)})'
            )
        );
    }
}
