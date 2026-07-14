<?php
namespace zin;

global $lang, $config;

$fields = defineFieldList('story.change');

$fields->field('reviewer')
    ->width('full')
    ->required(true)
    ->control('inputGroup')
    ->itemBegin('reviewer')
    ->control(array('control' => 'picker', 'disabled' => !data('forceReview') && data('needReview'), 'multiple' => true, 'items' => data('fields.reviewer.options'), 'value' => data('fields.reviewer.default')))
    ->itemEnd()
    ->itemBegin('needNotReview')
    ->control(array('control' => 'checkbox', 'id' => 'needNotReview', 'checked' => data('needReview'), 'rootClass' => 'w-32 ml-2 items-center', 'text' => $lang->story->needNotReview, 'value' => data('forceReview') ? 0 : 1))
    ->itemEnd();

$fields->field('title')
    ->width('full')
    ->required(data('fields.title.required'))
    ->control(function() use($lang)
    {
        return inputGroup
        (
            inputControl
            (
                input
                (
                    set::name('title'),
                    set::value(data('fields.title.default'))
                ),
                set::suffixWidth('40'),
                to::suffix
                (
                    colorPicker
                    (
                        set::name('color'),
                        set::type('color'),
                        set::value(data('fields.color.default')),
                        set::syncColor('#title, #storyTitle')
                    )
                )
            ),
            data('story.twins') ? span
            (
                setClass('input-group-addon'),
                checkbox
                (
                    setID('relievedTwins'),
                    set::name('relievedTwins'),
                    set::value(1),
                    $lang->story->relievedTwinsRelation
                )
            ) : null
        );
    });

$storyType = data('story.type') ? data('story.type') : 'story';

$fields->field('status')->width('1/3')->control('hidden')->value(data('fields.status.default'));
$fields->field('lastEditedDate')->width('1/3')->control('hidden')->value(data('story.lastEditedDate'));
$fields->field('spec')->required(strpos(",{$config->{$storyType}->change->requiredFields},", ",spec,") !== false)->width('full')->control(array('control' => 'editor', 'value' => data('fields.spec.default')));

$pageFields = data('fields') ? data('fields') : $config->story->form->change;
unset($pageFields['reviewer'], $pageFields['title'], $pageFields['color'], $pageFields['status'], $pageFields['lastEditedDate'], $pageFields['spec']);

foreach($pageFields as $field => $attr)
{
    $fieldName = zget($attr, 'name', $field);
    $control   = array();
    $control['type'] = !empty($attr['control']) ? $attr['control'] : 'input';
    if(!empty($attr['options'])) $control['items'] = $attr['options'];

    $fields->field($fieldName)->label(!empty($attr['title']) ? $attr['title'] : null)->required($attr['required'])->width('full')->control($control)->value($attr['default']);
}

$fields->field('comment')->label($lang->comment)->width('full')->control('editor');

$fields->field('files')->width('full')->control('fileSelector');

$fields->field('checkAffection')->width('full')->hidden($config->vision == 'or');
