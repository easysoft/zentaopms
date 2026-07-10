<?php
namespace zin;

global $lang;

$fields = defineFieldList('story.activate');

$fields->field('assignedTo')->width('1/3')->control('picker')->items(data('users'))->value(data('story.closedBy'));
if(!empty(data('story.twins')))
{
    $fields->field('assignSyncTip')->width('full')->label('')->control(array('control' =>'formTips', 'icon' => 'exclamation-sign', 'text' => $lang->story->assignSyncTip, 'theme' => 'black'));
}
$fields->field('comment')->label($lang->comment)->width('full')->control('editor');
