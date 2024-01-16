<?php
namespace zin;
global $lang,$config;

$fields = defineFieldList('task.create', 'task');

$nameWidth = 'w-1/2';
if(empty(data('features.story'))) $nameWidth .= ' full:w-full';
if(data('execution.type') == 'kanban') $nameWidth .= ' lite:w-full';
$fields->field('name')->className($nameWidth);

if(!isAjaxRequest('modal'))
{
    $fields->field('after')
        ->label($lang->task->afterSubmit)
        ->width('full')
        ->control(array('type' => 'radioList', 'inline' => true))
        ->value(data('task.id') ? 'continueAdding' : 'toTaskList')
        ->items(empty(data('features.story')) ? array('toTaskList' => $lang->task->afterChoices['toTaskList']) : $config->task->afterOptions);
}
