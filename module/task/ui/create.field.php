<?php
namespace zin;
global $lang,$config;

$fields = defineFieldList('task.create', 'task');

if(!isAjaxRequest('modal'))
{
    $fields->field('after')
        ->label($lang->task->afterSubmit)
        ->width('full')
        ->control(array('type' => 'radioList', 'inline' => true))
        ->value(!empty(data('task.id') ? 'toTaskList' : 'continueAdding'))
        ->items($config->task->afterOptions);
}
