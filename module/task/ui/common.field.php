<?php
namespace zin;
global $lang, $config;

$fields = defineFieldList('task');

$fields->field('execution')
    ->hidden(data('execution.type') == 'kanban' && $config->vision != 'lite')
    ->control('picker')
    ->items(data('executions'))
    ->value(data('execution.id'));

$fields->field('type')
    ->checkbox(array('text' => $lang->task->selectTestStory, 'name' => 'selectTestStory'))
    ->control('picker')
    ->items($lang->task->typeList)
    ->value(data('task.type'));

$fields->field('module')
    ->foldable()
    ->checkbox(array('text' => $lang->task->allModule, 'name' => 'isShowAllModule'))
    ->control(array('type' => 'picker', 'required' => true))
    ->items(data('modulePairs'))
    ->value(data('task.module'));

$fields->field('story')
    ->foldable()
    ->control('picker')
    ->items(data('stories'))
    ->value(data('task.story'));

$fields->field('name')
    ->control('colorInput', array('colorValue' => data('task.color')))
    ->value(data('task.name'));

$fields->field('assignTo')
    ->label($lang->task->assignedTo)
    ->checkbox(array('text' => $lang->task->multiple, 'name' => 'multiple'))
    ->control('picker')
    ->items(data('members'))
    ->value(data('task.assignedTo'));

$fields->field('datePlan')
    ->lable($lang->task->datePlan)
    ->control('inputGroup')
    ->itemBegin('estStarted')->control('datePicker')->placeholder($lang->task->estStarted)->value(data('task.estStarted'))->itemEnd()
    ->item(array('control' => 'span', 'text' => '-'))
    ->itemBegin('deadline')->control('datePicker')->placeholder($lang->task->deadline)->value(data('task.deadline'))->itemEnd();

$fields->field('pri')
    ->label($lang->task->pri)
    ->width('1/4')
    ->control('priPicker')
    ->items($lang->task->priList)
    ->value('3');

$fields->field('estimate')
    ->label($lang->task->estimateLabel)
    ->width('1/4');

$fields->field('desc')
    ->width('full')
    ->control('editor');

$fields->field('files')
    ->width('full')
    ->control('upload');

$fields->field('mailto')
    ->foldable()
    ->width('full')
    ->control('picker')
    ->multiple(true)
    ->items(data('users'));
