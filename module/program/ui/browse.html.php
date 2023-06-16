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
        if($delay > 0)
        {
            $program->postponed = true;
            $program->delayInfo = sprintf($lang->project->delayInfo, $delay);
        }
    }

    /* PM. */
    if(!empty($program->PM))
    {
        $userName = zget($users, $program->PM);
        $program->PMAvatar = $usersAvatar[$program->PM];
        $program->PM       = $userName;
    }

    /* Calculate budget.*/
    $programBudget   = $this->loadModel('project')->getBudgetWithUnit($program->budget);
    $program->budget = $program->budget != 0 ? zget($lang->project->currencySymbol, $program->budgetUnit) . ' ' . $programBudget : $lang->project->future;

    /* Progress. */
    if(isset($progressList[$program->id])) $program->progress = round($progressList[$program->id]);

    $program->isParent = false;
    if($program->parent > 0 and isset($programs[$program->parent])) $programs[$program->parent]->isParent = true;

    /* Set invested hours. */
    if(!isset($program->invested)) $program->invested = 0;
    if(str_contains($program->end, LONG_TIME)) $program->end = $lang->program->longTime;

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

jsVar('langManDay', $lang->program->manDay);
jsVar('summeryTpl', $summary);

featureBar
(
    set::current($status),
    set::linkParams("status={key}&orderBy=$orderBy"),
    li(searchToggle(set::module('program')))
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

$footToolbar = common::hasPriv('project', 'batchEdit') ? array('items' => array(array('text' => $lang->project->edit, 'class' => 'btn batch-btn size-sm primary', 'data-url' => createLink('project', 'batchEdit')))) : null;
dtable
(
    set::cols($cols),
    set::data($data),
    set::nested(true),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::canRowCheckable(jsRaw("function(rowID){return this.getRowInfo(rowID).data.type == 'project';}")),
    set::footPager(usePager()),
    set::footer(jsRaw('function(){return window.footerGenerator.call(this);}')),
    set::customCols(true),
    set::userMap($this->loadModel('user')->getPairs('noletter|pofirst')),
    set::footToolbar($footToolbar),
);

render();
