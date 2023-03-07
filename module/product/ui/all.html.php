<?php
namespace zin;

$cols = array_values($config->product->all->dtable->fieldList);
foreach($cols as $idx => $col)
{
    if($col['name'] == 'name')
    {
        unset($cols[$idx]['width']);
        $cols[$idx]['minWidth']     = 200;
        $cols[$idx]['nestedToggle'] = true;
    }

    if($col['name'] != 'actions') continue;

    $cols[$idx]['actionsMap'] = array(
        'edit'      => array('icon'=> 'icon-edit',         'hint'=> '编辑'),
        'group'     => array('icon'=> 'icon-group',        'hint'=> '团队'),
        'split'     => array('icon'=> 'icon-split',        'hint'=> '添加子项目集'),
        'delete'    => array('icon'=> 'icon-trash',        'hint'=> '删除', 'text' => '删除'),
        'close'     => array('icon'=> 'icon-off',          'hint'=> '关闭'),
        'start'     => array('icon'=> 'icon-start',        'hint'=> '开始'),
        'pause'     => array('icon'=> 'icon-pause',        'text'=> '挂起项目集'),
        'active'    => array('icon'=> 'icon-magic',        'text'=> '激活项目集'),
        'other'     => array('type'=> 'dropdown',          'hint'=> '其他操作', 'caret' => true),
        'link'      => array('icon'=> 'icon-link',         'text'=> '关联产品', 'name' => 'link'),
        'more'      => array('icon'=> 'icon-ellipsis-v',   'hint'=> '更多', 'type' => 'dropdown', 'caret' => false),
        'whitelist' => array('icon'=> 'icon-shield-check', 'text'=> '项目白名单', 'name' => 'whitelist'),
    );
    $cols[$idx]['type']  = 'actions';
    $cols[$idx]['width'] = 128;
}

/* TODO implements extend fields. */
$extendFields = $this->product->getFlowExtendFields();

$data         = array();
$totalStories = 0;
foreach($productStructure as $programID => $program)
{
    if(isset($programLines[$programID]))
    {
        foreach($programLines[$programID] as $lineID => $lineName)
        {
            if(!isset($program[$lineID]))
            {
                $program[$lineID] = array();
                $program[$lineID]['product']  = '';
                $program[$lineID]['lineName'] = $lineName;
            }
        }
    }

    /* ALM mode with more data. */
    if(isset($program['programName']) and $config->systemMode == 'ALM')
    {
        $item = new \stdClass();

        $item->programPM = '';
        if(!empty($program['programPM']))
        {
            /* TODO generate avatar and link. */
            $programPM = $program['programPM'];
            $userName  = zget($users, $programPM);
            // echo html::smallAvatar(array('avatar' => $usersAvatar[$programPM], 'account' => $programPM, 'name' => $userName), 'avatar-circle avatar-top avatar-' . zget($userIdPairs, $programPM));

            $userID = isset($userIdPairs[$programPM]) ? $userIdPairs[$programPM] : '';
            // echo html::a($this->createLink('user', 'profile', "userID=$userID", '', true), $userName, '', "title='{$userName}' class='iframe' data-width='600'");

            $item->programPM = $userName;
            $item->PO        = $userName;
        }

        $totalStories = $program['finishClosedStories'] + $program['unclosedStories'];

        $item->name             = $program['programName'];
        $item->id               = $program['id'];
        $item->type             = 'program';
        $item->level            = 1;
        $item->asParent         = true;
        $item->programName      = $program['programName'];
        $item->draftStories     = $program['draftStories'];
        $item->activeStories    = $program['activeStories'];
        $item->changingStories  = $program['changingStories'];
        $item->reviewingStories = $program['reviewingStories'];
        $item->totalStories     = ($totalStories == 0 ? 0 : round($program['finishClosedStories'] / $totalStories, 3) * 100) . '%';
        $item->unResolvedBugs   = $program['unResolvedBugs'];
        $item->totalBugs        = (($program['unResolvedBugs'] + $program['fixedBugs']) == 0 ? 0 : round($program['fixedBugs'] / ($program['unResolvedBugs'] + $program['fixedBugs']), 3) * 100) . '%';
        $item->plans            = $program['plans'];
        $item->releases         = $program['releases'];
        /* TODO attach extend fields. */
        $item->actions          = 'close|other:-pause,active|group|-edit|more:delete,link';

        $data[] = $item;
    }

    foreach($program as $lineID => $line)
    {

        /* ALM mode with Product Line. */
        if(isset($line['lineName']) and isset($line['products']) and is_array($line['products']) and $config->systemMode == 'ALM')
        {
            $totalStories = (isset($line['finishClosedStories']) ? $line['finishClosedStories'] : 0) + (isset($line['unclosedStories']) ? $line['unclosedStories'] : 0);

            $item = new \stdClass();
            $item->name             = $line['lineName'];
            $item->id               = $line['id'];
            $item->type             = 'productLine';
            $item->asParent         = true;
            $item->programName      = $line['lineName'];
            $item->draftStories     = $line['draftStories'];
            $item->activeStories    = $line['activeStories'];
            $item->changingStories  = $line['changingStories'];
            $item->reviewingStories = $line['reviewingStories'];
            $item->totalStories     = ($totalStories == 0 ? 0 : round((isset($line['finishClosedStories']) ? $line['finishClosedStories'] : 0) / $totalStories, 3) * 100) . '%';
            $item->unResolvedBugs   = $line['unResolvedBugs'];
            $item->totalBugs        = ((isset($line['fixedBugs']) and ($line['unResolvedBugs'] + $line['fixedBugs'] != 0)) ? round($line['fixedBugs'] / ($line['unResolvedBugs'] + $line['fixedBugs']), 3) * 100 : 0) . '%';
            $item->plans            = $line['plans'];
            $item->releases         = $line['releases'];
            /* TODO attach extend fields. */
            $item->actions          = 'close|other:-pause,active|group|-edit|more:delete,link';

            $data[] = $item;
        }

        /* Products of Product Line. */
        if(isset($line['products']) and is_array($line['products']))
        {
            foreach($line['products'] as $productID => $product)
            {
                $item = new \stdClass();

                if(!empty($product->PO))
                {
                    $userName  = zget($users, $product->PO);
                    //echo html::smallAvatar(array('avatar' => $usersAvatar[$product->PO], 'account' => $product->PO, 'name' => $userName), 'avatar-circle avatar-' . zget($userIdPairs, $product->PO));

                    $userID = isset($userIdPairs[$product->PO]) ? $userIdPairs[$product->PO] : '';
                    //echo html::a($this->createLink('user', 'profile', "userID=$userID", '', true), $userName, '', "title='{$userName}' class='iframe' data-width='600'");

                    $item->PO = $userName;
                }
                $totalStories = $product->stories['finishClosed'] + $product->stories['unclosed'];

                $item->name             = $product->name; /* TODO replace with <a> */
                $item->id               = $product->id;
                $item->type             = 'project';
                $item->level            = 2;
                $item->asParent         = false;
                $item->programName      = $product->name; /* TODO replace with <a> */
                $item->draftStories     = $product->stories['draft'];
                $item->activeStories    = $product->stories['active'];
                $item->changingStories  = $product->stories['changing'];
                $item->reviewingStories = $product->stories['reviewing'];
                $item->totalStories     = ($totalStories == 0 ? 0 : round($product->stories['finishClosed'] / $totalStories, 3) * 100) . '%';
                $item->unResolvedBugs   = $product->unResolved;
                $item->totalBugs        = (($product->unResolved + $product->fixedBugs) == 0 ? 0 : round($product->fixedBugs / ($product->unResolved + $product->fixedBugs), 3) * 100) . '%';
                $item->plans            = $product->plans;
                $item->releases         = $product->releases;
                $item->parent           = $product->program ? $product->program : '';
                /* TODO attach extend fields. */
                $item->actions          = 'close|other:-pause,active|group|-edit|more:delete,link';

                $data[] = $item;
            }
        }
    }
}

$footer = array(
    'items' => array(
        array('type' => 'info', 'text' => '共 {recTotal} 项'),
        array('type' => 'info', 'text' => '{page}/{pageTotal}'),
    ),
    'page' => 1,
    'recTotal' => 101,
    'recPerPage' => 10,
    'linkCreator' => '#?page{page}&recPerPage={recPerPage}'
);

\common::sortFeatureMenu();
$statuses = array();
foreach ($lang->product->featureBar['all'] as $key => $text)
{
    $statuses[] = array(
        'text'   => $text,
        'active' => ($key == $browseType),
        'url'    => createLink($this->moduleName, $this->methodName, 'all', "browseType=$key&orderBy=$orderBy"),
        'class'  => 'btn btn-link'
    );
}

$others = array();
if (\common::hasPriv('product', 'batchEdit'))
{
    $others[] = array(
        'text'    => $lang->product->edit,
        'checked' => $this->cookie->editProject,
        'type'    => 'checkbox'
    );
}
$others[] = array(
    'id'    => 'searchBtn',
    'type'  => 'button',
    'icon'  => 'search',
    'text'  => $lang->product->searchStory,
    'class' => 'ghost'
);

$btnGroup = array();
$btnGroup[] = array(
    'text'  => $lang->product->export,
    'icon'  => 'export',
    'class' => 'btn secondary',
    'url'   => createLink('product', 'export', $browseType, "status=$browseType&orderBy=$orderBy"),
);
if($config->systemMode == 'ALM')
{
    $btnGroup[] = array(
        'text'  => $lang->product->line,
        'icon'  => 'edit',
        'class' => 'btn secondary',
        'url'   => createLink('product', 'manageLine', $browseType),
    );
}
$btnGroup[] = array(
    'text'  => $lang->product->create,
    'icon'  => 'plus',
    'class' => 'btn primary',
    'url'   => createLink('product', 'create')
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
    var el = document.getElementById('searchBtn');
    if( el ) {
        el.addEventListener('click', function(event){
            var searchPanel = document.getElementById('searchPanel');
            if(!searchPanel) return;
            searchPanel.classList.toggle('hidden');
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

page
(
    header(),
    pagemain
    (
        mainmenu
        (
            set('statuses', $statuses),
            set('others',   $others),
            set('btnGroup', $btnGroup)
        ),
        panel
        (
            setId('searchPanel'),
            to('body', searchform(set($options)))
        ),
        dtable
        (
            set('width', '100%'),
            set('cols',  $cols),
            set('data',  $data),
            set('footPager', $footer),
            set('footToolbar', array('items' => array(array('size' => 'sm', 'text' => '编辑', 'btnType' => 'primary'))))
        )
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
