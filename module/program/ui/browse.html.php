<?php

namespace zin;

$cols   = array_values($config->program->dtable->fieldList);
$data   = array_values($programs);

foreach ($data as $row)
{
    if (!property_exists($row, 'progress'))
    {
        if (isset($progressList[$row->id])) $row->progress = $progressList[$row->id];
        else $row->progress = '';
    }

    if (!property_exists($row, 'actions')) $row->actions = array();
}

featureBar
(
    set::current($status),
    set::linkParams("status={key}&orderBy=$orderBy"),
    (hasPriv('project', 'batchEdit') && $programType != 'bygrid' && $hasProject === true) ? item
    (
        set::type('checkbox'),
        set::text($lang->project->edit),
        set::checked($this->cookie->editProject)
    ) : NULL,
    li(searchToggle())
);

toolbar
(
    hasPriv('project', 'create') ? item(set(array
    (
        'text'  => $lang->project->create,
        'icon'  => 'plus',
        'class' => 'btn secondary',
        'url'   => createLink('project', 'createGuide', "programID=0&from=PGM"),
    ))) : NULL,
    hasPriv('program', 'create') ? item(set(array
    (
        'text' => $lang->program->create,
        'icon'  => 'plus',
        'class' => 'btn primary',
        'url' => \helper::createLink('program', 'create')
    ))) : NULL
);

dtable
(
    set::className('shadow rounded'),
    set::cols($cols),
    set::data($data),
    set::footer(false)
);

programmenu
(
    set
    (
        array
        (
            'title' => '所有项目集',
            'subTitle' => '筛选项目集',
            'programs' => $data,
            'activeKey' => '7',
            'closeLink' => '#'
        )
    )
);

modulemenu
(
    set
    (
        array
        (
            'productID' => 1,
            'title' => '售后服务',
            'moduleSettingText' => '模块设置',
            'displaySettingText' => '显示设置',
            'activeKey' => '4',
            'closeLink' => '#'
        )
    )
);

render();
