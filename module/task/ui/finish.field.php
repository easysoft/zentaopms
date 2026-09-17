<?php
namespace zin;

global $lang;

$fields = defineFieldList('task.finish');

$buildConsumed = function()
{
    global $lang;
    $task = data('task');
    if(empty($task) || empty($task->team)) return null;
    return div(setClass('consumed'), $task->myConsumed . $lang->task->suffixHour);
};

$buildAssignedTo = function()
{
    $task    = data('task');
    $members = data('members');
    if(empty($task)) return null;

    if(!empty($task->team))
    {
        return inputGroup
        (
            input(set::name('assignedTo'), set::value(zget($members, $task->nextBy)), set::disabled(true)),
            formHidden('assignedTo', $task->nextBy)
        );
    }
    return picker(set::name('assignedTo'), set::value($task->nextBy), set::items($members));
};

$realStarted = '';
$task = data('task');
if(!empty($task))
{
    $realStarted = substr((string)$task->realStarted, 0, 19);
    if(helper::isZeroDate($realStarted)) $realStarted = '';
}

$buildRealStarted = function() use ($realStarted)
{
    $controls = array(datetimePicker(set::name('realStarted'), set::value($realStarted), set('disabled', !empty($realStarted))));
    if(!empty($realStarted)) $controls[] = formHidden('realStarted', $realStarted);
    return inputGroup(...$controls);
};

$fields->field('consumed')
    ->width('1/2')
    ->label($lang->task->my . $lang->task->hasConsumed)
    ->hidden(empty(data('task.team')))
    ->control($buildConsumed);

$fields->field('currentConsumed')
    ->width('1/2')
    ->label($lang->task->currentConsumed)
    ->control(array('control' => 'inputControl', 'suffix' => $lang->task->suffixHour, 'suffixWidth' => 20))
    ->value(0);

$fields->field('assignedTo')
    ->width('1/2')
    ->control($buildAssignedTo);

$fields->field('realStarted')
    ->width('1/2')
    ->label($lang->project->realBeganAB)
    ->required(true)
    ->control($buildRealStarted);

$fields->field('finishedDate')
    ->width('1/2')
    ->label($lang->project->realEndAB)
    ->control('datetimePicker')
    ->value(helper::now());

$fields->field('files')
    ->width('full')
    ->control('fileSelector');

$fields->field('comment')
    ->label($lang->comment)
    ->width('full')
    ->control('editor');
