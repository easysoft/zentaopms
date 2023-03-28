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

/* Render Cell. */
js
(
<<<RENDERCELL
var langWorkerDay = '{$lang->program->workerDay}';
var langPostponed = '{$lang->project->statusList['delay']}';

window.footerGenerator = function()
{
    const count = this.layout.allRows.filter((x) => x.data.type === "product").length;
    const statistic = '{$summary}'.replace('%s', ' ' + count + ' ');
    return [{children: statistic, className: "text-dark"}, "flex", "pager"];
}

window.renderCell = function(result, {col, row})
{
    if(col.name === 'name')
    {
        if(row.data.postponed) result[result.length] = {html:'<span class="label size-sm circle danger-pale">' + langPostponed + '</span>', className:'flex items-end w-full', style:{flexDirection:"column"}};
        return result;
    }

    if(col.name === 'budget')
    {
        result[0] = {html: '<div>' + row.data.budget + ' <span class="icon icon-exclamation-sign mr-2 text-danger"></span></div>', className:'flex items-end w-full items-end', style:{flexDirection:"column"}};
        return result;
    }

    if(col.name === 'invested')
    {
        result[0] = {html: '<div>' + row.data.invested + ' <small class="text-gray">' + langWorkerDay + '</small></div>', className:'flex items-end w-full items-end', style:{flexDirection:"column"}};
        return result;
    }

    return result;
}
RENDERCELL
);

set::title($lang->program->projectView);

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
    item(set(array(
        'text' => $lang->program->createProject,
        'icon' => 'plus',
        'class'=> 'btn secondary',
        'url'  => createLink('program', 'exportTable')
    ))),
    item(set(array(
        'text' => $lang->program->create,
        'icon' => 'plus',
        'class'=> 'btn primary',
        'url'  => createLink('program', 'exportTable')
    ))),
);

dtable
(
    set::className('shadow rounded'),
    set::cols($cols),
    set::data($data),
    set::footPager(usePager()),
    set::nested(true),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footer(jsRaw('function(){return window.footerGenerator.call(this);}'))
);

render();
