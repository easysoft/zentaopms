<?php
namespace zin;

global $lang, $config;

$fields = defineFieldList('task.editEffort');

$effort = data('effort');
$task   = data('task');
$readonly = !$effort->isLast || (!empty($task->team) && $effort->left == 0);

$fields->field('date')
    ->width('1/3')
    ->label($lang->task->date)
    ->required(true)
    ->control('date')
    ->value($effort->date);

$fields->field('consumed')
    ->width('1/4')
    ->label($lang->task->currentConsumed)
    ->required(true)
    ->control(array('control' => 'inputControl', 'suffix' => $lang->task->suffixHour, 'suffixWidth' => 20))
    ->value(helper::formatHours($effort->consumed));

$fields->field('left')
    ->width('1/4')
    ->label($lang->task->left)
    ->control(array('control' => 'inputControl', 'suffix' => $lang->task->suffixHour, 'suffixWidth' => 20, 'readonly' => $readonly))
    ->value(helper::formatHours($effort->left));

$fields->field('work')
    ->label($lang->task->work)
    ->required($config->edition != 'open')
    ->control('textarea')
    ->value(strip_tags($effort->work));
