<?php
namespace zin;

global $lang;

$fields = defineFieldList('bug.assignTo');

$fields->field('assignedTo')->width('1/3')->control('picker')->items(data('users'))->value(data('bug.assignedTo'));
$fields->field('mailto')->width('full')->control(array('control' => 'mailto', 'items' => data('users'), 'value' => data('bug.mailto')));
$fields->field('comment')->label($lang->comment)->width('full')->control('editor');
