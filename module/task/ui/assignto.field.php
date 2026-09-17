<?php
namespace zin;

global $lang;

$fields = defineFieldList('task.assignTo');

$fields->field('assignedTo')
    ->width('1/3')
    ->label($lang->task->assignedTo)
    ->control(array('control' => 'taskAssignedTo', 'manageLink' => data('manageLink') ? data('manageLink') : ''))
    ->items(data('members'))
    ->value((empty(data('task.team')) or strpos('done,cancel,closed', data('task.status')) !== false) ? data('task.assignedTo') : data('task.nextUser'));

$fields->field('left')
    ->width('1/3')
    ->label($lang->task->left)
    ->required(true)
    ->control(array('control' => 'inputControl', 'suffix' => $lang->task->suffixHour, 'suffixWidth' => 20))
    ->value(data('task.left'))
    ->hidden(!data('task') || data('task.status') == 'done' || data('task.status') == 'closed' || data('task.isParent') != '0');

$fields->field('comment')
    ->label($lang->comment)
    ->width('full')
    ->control('editor');
