<?php
namespace zin;

global $lang;

$fields = defineFieldList('bug.activate');

$fields->field('assignedTo')->width('1/3')->control('picker')->items(data('users'))->value(data('bug.resolvedBy'));
$fields->field('openedBuild')->width('1/2')->required(true)->control(array('control' => 'picker', 'multiple' => true))->items(data('builds'))->value(data('bug.openedBuild'));
$fields->field('comment')->label($lang->comment)->width('full')->control('editor');
$fields->field('files')->width('full')->control('fileSelector');
