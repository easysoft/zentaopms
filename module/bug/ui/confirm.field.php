<?php
namespace zin;

global $lang;

$fields = defineFieldList('bug.confirm');

$fields->field('assignedTo')->width('1/3')->control('picker')->items(data('users'))->value(data('bug.assignedTo'));
$fields->field('type')->width('1/3')->control('picker')->items($lang->bug->typeList)->value(data('bug.type'));
$fields->field('pri')->width('1/3')->control('priPicker')->items($lang->bug->priList)->value(data('bug.pri'));
$fields->field('deadline')->width('1/3')->control(array('control' => 'datePicker', 'id' => 'deadline'))->value(data('bug.deadline'));
$fields->field('mailto')->width('full')->control(array('control' => 'mailto', 'items' => data('users'), 'value' => data('bug.mailto')));
$fields->field('comment')->label($lang->comment)->width('full')->control('editor');
$fields->field('status')->width('1/3')->control('hidden')->value(data('bug.status'));
