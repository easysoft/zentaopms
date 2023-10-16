<?php
namespace zin;

$confirmDeleteLang['program'] = $lang->program->confirmDelete;
$confirmDeleteLang['project'] = $lang->project->confirmDelete;
jsVar('confirmDeleteLang',   $confirmDeleteLang);
jsVar('programBudgetLang',   $lang->program->programBudget);
jsVar('projectBudgetLang',   $lang->program->projectBudget);
jsVar('sumSubBudgetLang',    $lang->program->sumSubBudget);
jsVar('exceededBudgetLang',  $lang->program->exceededBudget);
jsVar('remainingBudgetLang', $lang->program->remainingBudget);
jsVar('langManDay',          $lang->program->manDay);
jsVar('pageSummary',         $summary);
jsVar('checkedSummary',      $lang->program->checkedProjects);

$this->loadModel('project');
$cols    = $this->loadModel('datatable')->getSetting('program');
$data    = array();
$parents = array();
foreach($programs as $program)
{
    if(empty($program->parent)) $program->parent = 0;

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

    /* Set the program manager and avatar. */
    if(!empty($program->PM))
    {
        $userName = zget($users, $program->PM, '');
        $program->PMAvatar = $usersAvatar[$program->PM];
        $program->PM       = $userName;
    }
    else
    {
        $program->PMAvatar = '';
        $program->PM       = '';
    }

    /* Calculate budget.*/
    $programBudget      = $this->project->getBudgetWithUnit($program->budget);
    $program->rawBudget = (int)$program->budget;
    $program->budget    = !empty($program->budget) ? zget($lang->project->currencySymbol, $program->budgetUnit) . ' ' . $programBudget : $lang->project->future;
    $parents[$program->parent][] = $program->id;

    /* Progress. */
    if(isset($progressList[$program->id])) $program->progress = round($progressList[$program->id]);

    $program->isParent = false;
    if($program->parent > 0 and isset($programs[$program->parent])) $programs[$program->parent]->isParent = true;
    if($program->parent > 0 and !isset($programs[$program->parent]))
    {
        $paths = str_replace(",{$program->parent},{$program->id},", ',', $program->path);
        $paths = explode(',', trim($paths, ','));
        $paths = array_reverse($paths);
        if($paths)
        {
            foreach($paths as $i => $parentID)
            {
                var_dump(isset($programs[$parentID]));
                if(isset($programs[$parentID]))
                {
                    $program->parent = $parentID;
                    break;
                }
                unset($paths[$i]);
            }
            $program->path  = ',' . implode(',', $paths) . ",{$program->id},";
            $program->grade = count($paths) + 1;
        }
    }

    /* Set invested hours. */
    if(!isset($program->invested)) $program->invested = 0;
    if(!is_null($program->end) && str_contains($program->end, LONG_TIME)) $program->end = $lang->program->longTime;

    /* Actions. */
    $program->actions = array();
    $actions          = $this->program->buildActions($program);
    foreach($actions as $action)
    {
        if(is_object($action))  $action->name = $program->type . '_' . $action->name;
        if(!is_object($action)) $action = $program->type . '_' . $action;

        if(isset($action->items)) foreach($action->items as $idx => $item) $action->items[$idx]->name = $program->type . '_' . $item->name;
        $program->actions[] = $action;
    }

    $data[$program->id] = $program;
}

$fnComputeSubBudget = function($programID, $sumBudget) use($parents, $data, &$fnComputeSubBudget)
{
    if(!isset($parents[$programID]))
    {
        $sumBudget += $data[$programID]->rawBudget;
    }
    else
    {
        foreach($parents[$programID] as $subID) $sumBudget = $fnComputeSubBudget($subID, $sumBudget);
    }
    return $sumBudget;
};

foreach($data as $programID => $program)
{
    if(!isset($parents[$programID])) continue;
    $program->subBudget = $fnComputeSubBudget($programID, 0);
}

/* Compute remaining budget. */
foreach($parents as $parentID => $subs)
{
    if(!isset($data[$parentID])) continue;

    $parentBudget = $data[$parentID]->rawBudget;
    if(empty($parentBudget)) continue;
    foreach($subs as $subID)
    {
        $subProgram = $data[$subID];
        if($subProgram->type == 'program') continue;
        $subProgram->remainingBudget = $parentBudget;
        array_map(function($programID) use ($data, $subProgram)
        {
            if($programID != $subProgram->id) $subProgram->remainingBudget -= $data[$programID]->rawBudget;
        }, $subs);
    }
}

foreach($data as $programID => $program)
{
    if($program->rawBudget == 0) continue;
    if($program->type == 'program' && !empty($program->subBudget) && $program->rawBudget < $program->subBudget)
    {
        $program->exceedBudget = $program->subBudget - $program->rawBudget;
        $program->exceedBudget = $this->project->getBudgetWithUnit($program->exceedBudget);
        $program->subBudget    = $this->project->getBudgetWithUnit($program->subBudget);
    }
    elseif($program->type == 'project' && !empty($program->remainingBudget) && $program->rawBudget > $program->remainingBudget)
    {
        $program->exceedBudget    = $program->rawBudget - $program->remainingBudget;
        $program->exceedBudget    = $this->project->getBudgetWithUnit($program->exceedBudget);
        $program->remainingBudget = $this->project->getBudgetWithUnit($program->remainingBudget);
    }
    $program->rawBudget = $this->project->getBudgetWithUnit($program->rawBudget);
}

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
        'text'  => $lang->program->createProject,
        'icon'  => 'plus',
        'class' => 'btn secondary',
        'url'   => $this->createLink('project', 'createGuide'),
        'data-toggle' => 'modal',
    ])),
    item(set
    ([
        'text' => $lang->program->create,
        'icon' => 'plus',
        'class'=> 'btn primary',
        'url'  => $this->createLink('program', 'create')
    ])),
);

$canBatchEdit = common::hasPriv('project', 'batchEdit');
dtable
(
    setID('projectviews'),
    set::cols($cols),
    set::data(array_values($data)),
    set::userMap($users),
    set::customCols(true),
    set::checkable($canBatchEdit),
    set::nested(true),
    set::className('shadow rounded'),
    set::footPager(usePager()),
    set::canRowCheckable(jsRaw("function(rowID){return this.getRowInfo(rowID).data.type == 'project';}")),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footToolbar(array
    (
        'type'  => 'btn-group',
        'items' => array(
            $canBatchEdit ? array
            (
                'text'      => $lang->edit,
                'className' => 'secondary size-sm batch-btn',
                'data-page' => 'batch',
                'data-formaction' => $this->createLink('project', 'batchEdit')
            ) : null,
        )
    )),
    set::checkInfo(jsRaw("function(checkedIDList){ return window.footerSummary(checkedIDList);}"))
);

render();
