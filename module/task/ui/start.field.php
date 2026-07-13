<?php
namespace zin;

global $lang;

$fields = defineFieldList('task.start');

$taskMode    = data('task.mode');
$currentTeam = data('currentTeam');

$fields->field('assignedTo')
    ->width('1/2')
    ->hidden($taskMode == 'multi')
    ->control('inputGroup')
    ->itemBegin('assignedTo')
    ->control($taskMode == 'linear' ? array('control' => 'picker', 'value' => data('assignedTo'), 'required' => true, 'disabled' => true, 'items' => data('users')) : array('control' => 'picker', 'value' => data('assignedTo'), 'name' => 'assignedTo', 'items' => data('members')))
    ->itemEnd()
    ->itemBegin('assignedTo')
    ->control(array('control' => 'input', 'name' => 'assignedTo', 'class' => 'hidden', 'value' => data('assignedTo'), 'disabled' => $taskMode != 'linear'))
    ->itemEnd();

$fields->field('realStarted')
    ->width('1/2')
    ->label($lang->task->realStarted)
    ->control('datetimePicker')
    ->value(helper::isZeroDate(data('task.realStarted')) ? helper::now() : data('task.realStarted'));

$fields->field('consumed')
    ->width('1/2')
    ->label($taskMode == 'linear' ? $lang->task->myConsumed : $lang->task->consumed)
    ->control(array('control' => 'inputControl', 'suffix' => $lang->task->suffixHour, 'suffixWidth' => 20))
    ->value(!empty($currentTeam) ? helper::formatHours((float)$currentTeam->consumed) : helper::formatHours((float)data('task.consumed')));

$fields->field('left')
    ->width('1/2')
    ->label($lang->task->left)
    ->control(array('control' => 'inputControl', 'suffix' => $lang->task->suffixHour, 'suffixWidth' => 20))
    ->value(!empty($currentTeam) ? helper::formatHours((float)$currentTeam->left) : helper::formatHours((float)data('task.left')));

$fields->field('comment')
    ->label($lang->comment)
    ->width('full')
    ->control(array('control' => 'editor', 'rows' => 5));
