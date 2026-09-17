<?php
namespace zin;

global $lang, $config;

$fields = defineFieldList('story.review');

$pageFields = data('fields') ? data('fields') : $config->story->form->review;
if(data('story.isParent') == '1') $pageFields['estimate']['readonly'] = true;

$pageFields['comment'] = array('type' => 'string', 'control' => 'editor', 'required' => false, 'default' => '', 'name' => 'comment', 'title' => $lang->comment, 'width' => 'full');

foreach($pageFields as $field => $attr)
{
    $width     = zget($attr, 'width', '1/3');
    $fieldName = zget($attr, 'name', $field);
    $control   = array();
    $control['type'] = $attr['control'];
    if(!empty($attr['options'])) $control['items'] = $attr['options'];
    if($field == 'duplicateStory')
    {
        $control['items']         = createLink('story', 'ajaxGetDuplicatedStories', "storyID=" . data('story.id') . "&productID=" . data('story.product'));
        $control['required']      = false;
        $control['maxItemsCount'] = 100;
    }

    $value = $field == 'estimate' ? helper::formatHours($attr['default']) : $attr['default'];

    if($field == 'result')       $control['data-call'] = 'switchShow';
    if($field == 'closedReason') $control['data-call'] = 'setStory';

    $control['data-on']     = 'change';
    $control['data-params'] = 'event';

    $fields->field($fieldName)->label(!empty($attr['title']) ? $attr['title'] : null)->required($attr['required'])->readonly(!empty($attr['readonly']))->width($width)->control($control)->value($value);

    if($field == 'closedReason') $fields->field($fieldName)->id('rejectedReasonBox');
    if($field == 'assignedTo')   $fields->field($fieldName)->hidden(!data('isLastOne'));

    if(in_array($field, array('assignedTo', 'duplicateStory', 'pri', 'estimate')))             $fields->field($fieldName)->id($field . 'Box');
    if(in_array($field, array('closedReason', 'duplicateStory', 'pri', 'estimate', 'status'))) $fields->field($fieldName)->hidden(true);
}

$fields->field('checkAffection')->width('full')->hidden(strpos('or,lite', $config->vision) !== false);
