<?php
namespace zin;

$cols = array_values($config->program->projectView->dtable->fieldList);

$data = array();
foreach($programs as $program)
{
    if(empty($program->parent)) $program->parent = null;

    /* Delay status. */
    if($program->status != 'done' and $program->status != 'closed' and $program->status != 'suspended')
    {
        $delay = helper::diffDate(helper::today(), $program->end);
        if($delay > 0) $program->postponed = true;
    }

    /* PM. */
    if(!empty($program->PM))
    {
        $userName = zget($users, $program->PM);
        $program->PMAvatar = $usersAvatar[$program->PM];
        $program->PM       = $userName;
    }

    /* Calculate budget.*/
    $programBudget = $this->loadModel('project')->getBudgetWithUnit($program->budget);
    $program->budget = $program->budget != 0 ? zget($lang->project->currencySymbol, $program->budgetUnit) . ' ' . $programBudget : $lang->project->future;

    /* Progress. */
    if(isset($progressList[$program->id])) $program->progress = round($progressList[$program->id]);

    $program->invested = 0;

    /* Actions. */
    $program->actions = array();
    $actions          = $this->program->buildActions($program);
    foreach($actions as $action)
    {
        if(is_object($action)) $action->name = $program->type . '_' . $action->name;
        else $action = $program->type . '_' . $action;

        if(isset($action->items)) foreach($action->items as $idx => $item) $action->items[$idx]->name = $program->type . '_' . $item->name;
        $program->actions[] = $action;
    }

    $data[] = $program;
}

jsVar('langManDay',    $lang->program->manDay);
jsVar('langPostponed', $lang->project->statusList['delay']);
jsVar('summeryTpl',    $summary);

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
    item(set
    ([
        'text' => $lang->program->createProject,
        'icon' => 'plus',
        'class'=> 'btn secondary',
        'url'  => createLink('program', 'exportTable')
    ])),
    item(set
    ([
        'text' => $lang->program->create,
        'icon' => 'plus',
        'class'=> 'btn primary',
        'url'  => createLink('program', 'create')
    ])),
);

dtable
(
    set::cols($cols),
    set::data($data),
    set::nested(true),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager()),
    set::footer(jsRaw('function(){return window.footerGenerator.call(this);}'))
);

render();
