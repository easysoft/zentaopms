<?php
namespace zin;
global $lang,$config;

$fields = defineFieldList('task.create', 'task');

$nameWidth = 'w-1/2';
if(empty(data('features.story'))) $nameWidth .= ' full:w-full';
if(data('execution.type') == 'kanban') $nameWidth .= ' lite:w-full';
$fields->field('name')->className($nameWidth);

$fields->field('storyBox')
    ->label($lang->task->story)
    ->checkbox(array('text' => $lang->task->syncStory, 'name' => 'copyButton'))
    ->hidden(!data('features.story'))
    ->foldable(data('features.story'))
    ->control('inputgroup')
    ->items(false)
    ->itemBegin('story')->control('picker')->items(data('stories'))->value(data('task.story'))->itemEnd()
    ->itemBegin()->control('btn', array('id' => 'preview', 'data-toggle' => 'modal', 'data-url' => '#', 'data-size' => 'lg', 'className' => 'hidden'))->icon('eye text-gray')->itemEnd();

if(!isAjaxRequest('modal'))
{
    $fields->field('after')
        ->label($lang->task->afterSubmit)
        ->width('full')
        ->control(array('type' => 'radioList', 'inline' => true))
        ->value(data('task.id') ? 'continueAdding' : 'toTaskList')
        ->items(empty(data('features.story')) ? array('toTaskList' => $lang->task->afterChoices['toTaskList']) : $config->task->afterOptions);
}

$fields->field('storyEstimate')
    ->hidden()
    ->control('input');

$fields->field('storyDesc')
    ->hidden()
    ->control('input');

$fields->field('storyPri')
    ->hidden()
    ->control('input')
    ->value('0');

$fields->field('taskName')
    ->hidden()
    ->control('input');

$fields->field('taskEstimate')
    ->hidden()
    ->control('input');
