<?php

namespace zin;

$cols   = array_values($config->program->dtable->fieldList);
$data   = array_values($programs);

foreach($data as $row)
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
    to::before(programMenu(set
    ([
        'title' => $lang->program->all,
        'programs' => $data,
        'activeKey' => '7',
        'closeLink' => '#'
    ]))),
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
        'url' => createLink('program', 'create')
    ))) : NULL
);

dtable
(
    set::cols($cols),
    set::data($data),
    set::rowHeight(40),
    set::footer(false)
);

render();
