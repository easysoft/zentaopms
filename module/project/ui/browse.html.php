<?php
namespace zin;

\commonModel::setMainMenu();
$navItems = array();
foreach (\customModel::getMainMenu() as $menuItem)
{
    $navItems[] = array(
        'text'   => $menuItem->text,
        'url'    => \commonModel::createMenuLink($menuItem, $app->tab),
        'active' => $menuItem->order === 1,
    );
}

/* Generate dropdown menus. */
$userMenu         = \commonModel::printUserBarZin();
$globalCreateMenu = \commonModel::printCreateListZin();
$switcherMenu     = \commonModel::printVisionSwitcherZin();

\common::sortFeatureMenu();
$statuses = array();
foreach ($lang->project->featureBar['browse'] as $key => $text)
{
    $statuses[] = array(
        'text' => $text . (($browseType == $key) ? "({$pager->recTotal})" : ''),
        'active' => $key === $browseType,
        'url' => \helper::createLink('project', 'browse', "programID=$programID&browseType=$key"),
        'class' => $key === $browseType ? '' : 'ghost'
    );
}

$others = array();
if (\common::hasPriv('project', 'batchEdit'))
{
    $others[] = array(
        'text'    => $lang->project->edit,
        'checked' => $this->cookie->showProjectBatchEdit ? 'checked' : null,
        'type'    => 'checkbox'
    );
}

if($browseType != 'bysearch')
{
    $others[] = array(
        'text'    => $lang->project->mine,
        'checked' => $this->cookie->involved ? 'checked' : null,
        'type'    => 'checkbox',
        'name'    => 'involved'
    );
}
$others[] = array(
    'id'    => 'bysearchTab',
    'type'  => 'button',
    'icon'  => 'search',
    'text'  => $lang->search->common,
    'class' => 'ghost'
);

$btnGroup = array();
$btnGroup[] = array(
    'text'  => $lang->export,
    'icon'  => 'export',
    'class' => 'btn secondary',
    'url'   => createLink('project', 'export', $browseType, "status=$browseType&orderBy=$orderBy"),
);
$btnGroup[] = array(
    'text'  => $lang->project->create,
    'icon'  => 'plus',
    'class' => 'btn primary',
    'url'   => createLink('project', 'create')
);

/* Search Form options. */
$searchOptions = <<<OPTIONS
{
    "formConfig": { "actions": "11", "method": "post" },
    "fields": [
        { "label": "项目集名称", "name": "name", "control": "input", "operator": "include", "defaultValue": "1111", "placeholder": "请填写" },
        { "label": "状态", "name": "status", "control": "select", "operator": "!=", "defaultValue": "wait",
            "values": {
                "": "",
                "wait": "未开始",
                "doing": "进行中",
                "suspended": "已挂起",
                "closed": "已关闭"
            }
        },
        { "label": "项目集描述", "name": "desc", "control": "input", "defaultValue": "11", "placeholder": "请填写1" },
        { "label": "负责人", "name": "PM", "control": "select", "defaultValue": "", "placeholder": "请填写2" },
        { "label": "创建日期", "name": "openedDate", "control": "date", "defaultValue": "" },
        { "label": "计划开始", "name": "begin", "control": "date", "defaultValue": "" },
        { "label": "计划完成", "name": "end", "control": "date", "defaultValue": "" },
        { "label": "由谁创建", "name": "openedBy", "control": "select", "defaultValue": "" },
        { "label": "最后编辑日期", "name": "lastEditedDate", "control": "date", "defaultValue": "" },
        { "label": "实际开始", "name": "realBegan", "control": "date", "defaultValue": "" },
        { "label": "实际完成日期", "name": "realEnd", "control": "date", "defaultValue": "" },
        { "label": "关闭日期", "name": "closedDate", "control": "date", "defaultValue": "" }
    ],
    "operators": [
        { "value": "=", "title": "=" },
        { "value": "!=", "title": "!=" },
        { "value": ">", "title": ">" },
        { "value": ">=", "title": ">=" },
        { "value": "<", "title": "<" },
        { "value": "<=", "title": "<=" },
        { "value": "include", "title": "包含" },
        { "value": "between", "title": "介于" },
        { "value": "notinclude", "title": "不包含" },
        { "value": "belong", "title": "从属于" }
    ],
    "savedQuery": [
        { "id": "1", "title": "条件11", "account": "11",
            "content": [
                { "fields": "status", "control": "select", "condition": "=", "value": "doing" },
                { "fields": "openedDate", "control": "date", "condition": "=", "value": "2022-11-15" },
                { "fields": "openedBy", "control": "input", "condition": "=", "value": "" },
                { "fields": "PM", "control": "select", "condition": "!=", "value": "" },
                { "fields": "openedDate", "control": "date", "condition": "include", "value": "" },
                { "fields": "begin", "control": "date", "condition": "=", "value": "" }
            ]
        }
    ],
    "andOr": [
        { "value": "and", "title": "并且" },
        { "value": "or", "title": "或者" }
    ],
    "groupName": [
        "第一组",
        "第二组"
    ],
    "savedQueryTitle": "已保存的查询条件",
    "saveSearch": {
        "text": "保存搜索条件",
        "config": {
            "data-toggle": "modal",
            "href": "#saveModal",
            "data-url": "/index.php?m=search&f=saveQuery&module=task&onMenuBar=yes"
        }
    }
}
OPTIONS;
$options = json_decode($searchOptions);

$jsSearch = <<<JSSEARCH
    var el = document.getElementById('bysearchTab');
    if( el ) {
        el.addEventListener('click', function(event){
            var searchPanel = document.getElementById('searchPanel');
            if(!searchPanel) return;
            searchPanel.classList.toggle('hidden');
        });
    }

    var involved = document.getElementsByName('involved');
    console.log(involved);
    if( involved ) {
        involved.forEach(function(invItem){
            invItem.addEventListener('click', function(event){
                console.log('>>>', event);
            });
        });
    }

    zui.create(
        "AjaxForm",
        "#saveForm",
        {
            "js-render":true,
            "rules":
            {
                "title":
                {
                    "required":true,
                    "errMsg":"请输入保存条件名"
                },
                "onLoad": function(e) {
                    console.log(">>>", e);
                },
                "onError": function(e) {
                    console.log('onError>>>', e);
                },
                headers: {
                    'Content-type': 'application/json; charset=UTF-8'
                }
            }
        }
    );
JSSEARCH;

$setting = $this->datatable->getSetting('project');
$cols    = array();
foreach($setting as $value)
{
    /* Hide columns. */
    if($value->id == 'status' and strpos(',all,bysearch,undone,', ",$browseType,") === false) $value->show = false;
    if($value->id == 'teamCount' and $browseType == 'all') $value->show = false;
    if(\commonModel::isTutorialMode() && ($value->id == 'PM' || $value->id == 'budget' || $value->id == 'teamCount')) $value->show = false;

    if(!$value->show) continue;

    $col = array(
        'name'  => $value->id,
        'title' => $value->title,
        //'width' => (is_numeric($value->width) ? intval($value->width) : $value->width)
    );
    if(isset($value->minWidth)) $col['minWidth'] = $value->minWidth;

    /* Process each column. */
    switch ($value->id)
    {
        case 'id':
            $col['checkbox']     = true;
            $col['nestedToggle'] = true;
            break;
        case 'name':
            /* TODO BUG, 'width' can not be 'auto'. */
            $col['width'] = 720;
            break;
        case 'progress':
            $col['type'] = 'circleProgress';
            break;
        case 'PM':
            $col['type'] = 'avatarBtn';
            break;
        case 'actions':
            $col['type'] = 'actions';
            $col['width'] = 160;
            /* TODO be configurable. */
            $col['actionsMap'] = array(
                'start'     => array('icon'=> 'icon-start',        'hint'=> '启动项目'),
                'close'     => array('icon'=> 'icon-off',          'hint'=> '关闭项目'),
                'pause'     => array('icon'=> 'icon-pause',        'text'=> '挂起项目'),
                'active'    => array('icon'=> 'icon-magic',        'text'=> '激活项目'),
                'edit'      => array('icon'=> 'icon-edit',         'hint'=> '编辑项目'),
                'group'     => array('icon'=> 'icon-group',        'hint'=> '团队成员'),
                'perm'      => array('icon'=> 'icon-lock',         'hint'=> '项目权限分组'),
                'delete'    => array('icon'=> 'icon-trash',        'hint'=> '删除',       'text'  => '删除'),
                'other'     => array('type'=> 'dropdown',          'hint'=> '其他操作',   'caret' => true),
                'link'      => array('icon'=> 'icon-link',         'text'=> '关联产品',   'name'  => 'link'),
                'more'      => array('icon'=> 'icon-ellipsis-v',   'hint'=> '更多',       'type'  => 'dropdown', 'caret' => false),
                'whitelist' => array('icon'=> 'icon-shield-check', 'text'=> '项目白名单', 'name'  => 'whitelist'),
            );
            break;
    }

    $cols[] = $col;
}

$data = array();
foreach($projectStats as $project)
{
    /* TODO statistic */
    $item     = new \stdClass();
    foreach($setting as $value) $this->project->printCellZin($value, $project, $users, $item, $programID);

    $data[] = $item;
}

page
(
    pageheader
    (
        pageheading
        (
            set('text', $lang->{$app->tab}->common),
            set('icon', $app->tab),
            set('url', \helper::createLink($app->tab, 'browse'))
        ),
        pagenavbar
        (
            setId('navbar'),
            set('items', $navItems)
        ),
        pagetoolbar
        (
            set('create',   array('href' => '#globalCreateMenu')),
            set('switcher', array('href' => '#switcherMenu', 'text' => '研发管理界面')),
            block('avatar', avatar(set('name', $app->user->account), set('avatar', $app->user->avatar), set('trigger', '#userMenu')))
        )
    ),
    pagemain
    (
        mainmenu
        (
            set('statuses', $statuses),
            set('others', $others),
            set('btnGroup', $btnGroup)
        ),
        panel
        (
            setId('searchPanel'),
            setClass('mb-3'),
            to('body', searchform(set($options)))
        ),
        dtable
        (
            set('cols', $cols),
            set('width', '100%'),
            set('data', $data)
        )
    ),
    dropdown
    (
        setId('userMenu'),
        set('items', $userMenu)
    ),
    dropdown
    (
        setId('globalCreateMenu'),
        set('items', $globalCreateMenu)
    ),
    dropdown
    (
        setId('switcherMenu'),
        set('items', $switcherMenu)
    ),
    modal
    (
        setId('saveModal'),
        set('type', 'none'),
        form
        (
            setId('saveForm'),
            setClass('validation form-group'),
            set('action', \helper::createLink('search', 'saveQuery', 'product')),
            set('method', 'POST'),
            div
            (
                setClass('flex flex-row justify-between'),
                input
                (
                    setId('title'),
                    set('name', 'title'),
                    setClass('form-control w-5/12'),
                    set('type', 'text'),
                    set('placeholder', '请输入保存条件名称')
                ),
                checkbox
                (
                    setId('common'),
                    set('name', 'common'),
                    setClass('w-3/12'),
                    '设为公共查询条件'
                ),
                checkbox
                (
                    setId('onMenuBar'),
                    set('name', 'onMenuBar'),
                    setClass('w-3/12'),
                    '显示在菜单栏'
                ),
                btn(
                    setClass('w-1/12 primary'),
                    set('data-type', 'submit'),
                    '保存'
                )
            )
        )
    ),
    h::js($jsSearch)
);
