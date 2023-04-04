<?php
namespace zin;

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
    'class' => ($browseType == 'bysearch' ? 'active' : 'ghost')
);

$btnGroup = array();
$btnGroup[] = array(
    'text'  => $lang->export,
    'icon'  => 'export',
    'class' => 'btn secondary',
    'url'   => createLink('project', 'export', $browseType, "status=$browseType&orderBy=$orderBy", 'json'),
);
$btnGroup[] = array(
    'text'  => $lang->project->create,
    'icon'  => 'plus',
    'class' => 'btn primary',
    'url'   => createLink('project', 'create', '', 'json')
);

$buildFormURL   = createLink('search', 'buildForm', 'module=project', 'json');
$formActionURL  = createLink('search', 'buildQuery', '', 'json');
$saveQueryURL   = createLink('search', 'saveQuery', 'module=project&onMenuBar=no', 'json');
$deleteQueryURL = createLink('search', 'deleteQuery', 'queryID=myQueryID', 'json');
$applyQueryURL  = $actionURL;

$jsSearch = <<<JSSEARCH
var browseType = '{$browseType}';
var buildFormURL = '{$buildFormURL}';
var formActionURL = '{$formActionURL}';
var saveQueryURL = '{$saveQueryURL}';
var deleteQueryURL = '{$deleteQueryURL}';
var applyQueryURL = '{$applyQueryURL}';
var module = '{$this->moduleName}';

function initSaveSearch()
{
    var saveModal = document.getElementById('saveModal');
    if(!saveModal) return;

    var searchForm = saveModal.querySelector('form');
    if(!searchForm) return;

    var btnSave = searchForm.querySelector('button');
    if(!btnSave) return;

    btnSave.addEventListener('click', function(event)
    {
        var data = new FormData(searchForm);
        data.set('module', module);
        if(data.get('common') === 'on') data.set('common', 1);

        axios.post(saveQueryURL, data)
            .then(function(response)
            {
                if(response.data === 'success')
                {
                    event.target.closest('div.modal').classList.remove('show');
                }
            })
            .catch(function(error){console.error(error);});
    });
}

function initSearchButton()
{
    /* Click search button. */
    var el = document.getElementById('bysearchTab');
    if(!el) return;

    el.addEventListener('click', function(event)
    {
        var btnSearch      = this;
        btnSearch.disabled = true;

        /* Show search panel. */
        var searchPanel = document.getElementById('searchPanel');
        if(!searchPanel) return;
        searchPanel.classList.toggle('hidden');

        /* Ajax: Get search form data. */
        axios.get(buildFormURL)
            .then(function(response)
            {
                if(response.data.status != 'success')
                {
                    console.error(response.data);
                    return;
                }

                /* Search fields. */
                var data   = JSON.parse(response.data.data);
                var fields = [];
                for( var fieldName in data.searchFields )
                {
                    var params = data.fieldParams[fieldName];
                    var field  = new Object();

                    field.label        = data.searchFields[fieldName];
                    field.name         = fieldName;
                    field.control      = params ? ((params.class === 'date') ? 'date' : params.control) : '';
                    field.operator     = params ? params.operator : '';
                    field.values       = params ? params.values : '';
                    field.defaultValue = '';
                    field.placeholder  = '';

                    fields.push(field);
                };

                /* Saved queries. */
                var savedQueries = Object.keys(data.queries).map((key) => {
                    if(typeof data.queries[key] !== 'object') return false;
                    return data.queries[key];
                });

                /* Render search form. */
                zui.create('searchForm', "[id='queryForm']", {
                    "formConfig": { "action": "", "method": "post" },
                    "fields": fields,
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
                    "savedQuery": savedQueries,
                    "savedQueryTitle": "已保存的查询条件",
                    "andOr": [
                        { "value": "and", "title": "并且" },
                        { "value": "or", "title": "或者" }
                    ],
                    "groupName": [
                        "第一组",
                        "第二组"
                    ],
                    "saveSearch": {
                        "text": "保存搜索条件",
                        hasPermission: true,
                        "config": {
                            'data-toggle': 'modal',
                            'href': '#saveModal'
                        }
                    },
                    "applyQueryURL": applyQueryURL,
                    "onDeleteQuery": (e, id) => {
                        e.stopPropagation();
                        axios.get(deleteQueryURL.replace('myQueryID', id))
                            .then(function(response){
                                if(response.data === 'success')
                                {
                                    e.target.closest('div').remove();
                                }
                            })
                            .catch(function(error){console.error(error);});
                    },
                    "submitForm": (e) => {
                        const formData = new FormData(e.target.closest('form'));
                        formData.set('actionURL', data.actionURL);
                        formData.set('module', data.module);
                        formData.set('groupItems', data.groupItems);
                        formData.set('formType', 'lite');

                        axios.post(formActionURL, formData)
                            .then(function(response){
                                window.location.replace(data.actionURL);
                            })
                            .catch(function(error){console.error(error);});
                    },
                    "formSession": data.formSession
                });
            })
            .catch(function(error){
                console.error(error);
            })
            .finally(function(){
                btnSearch.disabled = false;
            });
    });

    /* Trigger search button. */
    if( browseType == 'bysearch')
    {
        el.dispatchEvent(new Event('click'));
    }
}

window.addEventListener('DOMContentLoaded', (event) => {
    initSearchButton();
    initSaveSearch();

    var involved = document.getElementsByName('involved');
    if( involved )
    {
        involved.forEach(function(invItem){
            invItem.addEventListener('click', function(event){ });
        });
    }
});

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
    $item     = new \stdClass();
    foreach($setting as $value) $this->project->printCellZin($value, $project, $users, $item, $programID);

    $data[] = $item;
}

set('ajax', true);
h::js($jsSearch);

header();

pageMain
(
    mainMenu
    (
        set('statuses', $statuses),
        set('others', $others),
        set('btnGroup', $btnGroup)
    ),
    panel
    (
        setId('searchPanel'),
        setClass('mb-3 hidden'),
        to('body', div(setId('queryForm')))
    ),
    dtable
    (
        set('cols', $cols),
        set('width', '100%'),
        set('data', $data)
    )
);

modal
(
    setId('saveModal'),
    set('type', 'none'),
    form
    (
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
);

render();
