<?php
namespace zin;

$setting = $this->datatable->getSetting('project');
$cols    = [];
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
                'more'      => array('icon'=> 'icon-ellipsis-v',   'hint'=> '更多',       'type'  => 'dropdown', 'caret' => false, 'className' => 'menu-dtable-actions'),
                'whitelist' => array('icon'=> 'icon-shield-check', 'text'=> '项目白名单', 'name'  => 'whitelist'),
            );
            break;
    }

    $cols[] = $col;
}

$data = array();
foreach($projectStats as $project)
{
    $item = new stdClass();
    foreach($setting as $value) $this->project->printCellZin($value, $project, $users, $item, $programID);

    $data[] = $item;
}

featureBar
(
    hasPriv('project', 'batchEdit') ? item
    (
        set::type('checkbox'),
        set::text($lang->project->edit),
        set::checked($this->cookie->showProjectBatchEdit)
    ) : NULL,
    $browseType != 'bysearch' ? item
    (
        set::type('checkbox'),
        set::name('involved'),
        set::text($lang->project->mine),
        set::checked($this->cookie->involved)
    ) : NULL,
    li(searchToggle()),
);

toolbar
(
    item(set(
    [
        'text'  => $lang->export,
        'icon'  => 'export',
        'class' => 'btn secondary',
        'url'   => createLink('project', 'export', $browseType, "status=$browseType&orderBy=$orderBy", 'json'),
    ])),
    item(set(
    [
        'text'  => $lang->project->create,
        'icon'  => 'plus',
        'class' => 'btn primary',
        'url'   => createLink('project', 'create', '', 'json')
    ])),
);

dtable
(
    set::cols($cols),
    set::data($data)
);

render();
