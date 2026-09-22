<?php
namespace zin;

global $lang;

$fields = defineFieldList('story.close');

$fields->field('closedReason')->width('1/3')->control(array('control' => 'picker', 'data-on' => 'change', 'data-call' => 'setStory', 'data-params' => 'event'))->items(data('reasonList'));
$fields->field('duplicateStory')
    ->id('duplicateStoryBox')
    ->width('1/2')
    ->control(array('control' => 'picker', 'items' => createLink('story', 'ajaxGetDuplicatedStories', "storyID=" . data('story.id') . "&productID=" . data('story.product'))))
    ->required(true)
    ->items(data('users'))
    ->value(data('story.closedBy'));

$fields->field('comment')->label($lang->comment)->width('full')->control('editor');
