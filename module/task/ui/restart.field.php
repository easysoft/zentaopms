<?php
namespace zin;
global $lang;

$fields = defineFieldList('task.restart');

$taskMode    = data('task.mode');
$currentTeam = data('currentTeam');

$buildConsumed = function()
{
    global $lang;
    $currentTeam = data('currentTeam');
    $task        = data('task');
    $value       = !empty($currentTeam) ? helper::formatHours((float)$currentTeam->consumed) : helper::formatHours((float)$task->consumed);
    $disabled    = (!empty($currentTeam) || $task->isParent) ? true : false;
    return inputControl
    (
        input(set::name('consumed'), set::value($value), set::disabled($disabled)),
        !empty($currentTeam) ? formHidden('consumed', (float)$currentTeam->consumed) : null,
        $task->isParent      ? formHidden('consumed', (float)$task->consumed)        : null,
        to::suffix($lang->task->suffixHour),
        set::suffixWidth(20)
    );
};

$buildLeft = function()
{
    global $lang;
    $currentTeam = data('currentTeam');
    $task        = data('task');
    $value       = !empty($currentTeam) ? helper::formatHours((float)$currentTeam->left) : helper::formatHours((float)$task->left);
    return inputControl
    (
        input(set::name('left'), set::value($value), set::disabled($task->isParent ? true : false)),
        $task->isParent ? formHidden('left', $task->left) : null,
        to::suffix($lang->task->suffixHour),
        set::suffixWidth(20)
    );
};

$fields->field('assignedTo')
    ->width('1/3')
    ->hidden($taskMode == 'multi')
    ->control('inputGroup')
    ->itemBegin('assignedTo')
    ->control($taskMode == 'linear' ? array('control' => 'picker', 'value' => data('assignedTo'), 'required' => true, 'disabled' => true, 'items' => data('users')) : (data('canRecordEffort') ? array('control' => 'picker', 'value' => data('assignedTo'), 'name' => 'assignedTo', 'items' => data('members')) : array()))
    ->itemEnd()
    ->itemBegin('assignedTo')
    ->control(array('control' => 'input', 'name' => 'assignedTo', 'class' => 'hidden', 'value' => data('assignedTo'), 'disabled' => $taskMode != 'linear'))
    ->itemEnd();

$fields->field('realStarted')
    ->width('1/3')
    ->label($lang->task->realStarted)
    ->control('datetimePicker')
    ->value(helper::isZeroDate(data('task.realStarted')) ? helper::now() : data('task.realStarted'));

$fields->field('consumed')
    ->width('1/3')
    ->label(!empty($currentTeam) ? $lang->task->myConsumed : $lang->task->consumed)
    ->control($buildConsumed);

$fields->field('left')
    ->width('1/3')
    ->label($lang->task->left)
    ->control($buildLeft);

$fields->field('comment')
    ->label($lang->comment)
    ->width('full')
    ->control(array('control' => 'editor', 'rows' => 5));
