<?php
namespace zin;

global $lang;

$assignToList = data('story.status') == 'closed' ? array('closed' => 'Closed') : data('users');

$fields = defineFieldList('story.assignTo');

$fields->field('assignedTo')->width('1/3')->control('picker')->items($assignToList)->value(data('story.assignedTo'));
if(!empty(data('story.twins')))
{
    $fields->field('assignSyncTip')->width('full')->label('')->control(array('control' =>'formTips', 'icon' => 'exclamation-sign', 'text' => $lang->story->assignSyncTip, 'theme' => 'black'));
}
$fields->field('comment')->label($lang->comment)->width('full')->control('editor');
$fields->field('status')->width('1/3')->control('hidden')->value(data('story.status'));
