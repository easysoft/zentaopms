<?php
namespace zin;

global $lang, $app, $config;

$storyType      = data('story.type') ?: 'story';
$fieldData      = data('fields')     ?: array();
$canEditContent = str_contains(',draft,changing,', ',' . (data('story.status') ?: '') . ',');
$forceReview    = $app->control->story->checkForceReview($storyType);
$storyEstimate  = data('story.estimate') ? helper::formatHours(data('story.estimate')) : 0;
$assignedToList = data('story.status') == 'closed' ? array('closed' => 'Closed') : data('users');
$planCount      = !empty(data('story.planTitle')) ? count(data('story.planTitle')) : 0;
$multiplePlan   = (data('product.type') != 'normal' && empty(data('story.branch')) && $planCount > 1) || ($storyType != 'story');
$showPlan       = $config->vision == 'or' ? false : true;

$minStage    = data('story.stage') ?: '';
$stageList   = implode(',', array_keys($lang->story->stageList));
$minStagePos = strpos($stageList, $minStage);
if(!empty(data('story.stages')) && isset($fieldData['stage']['options']))
{
    foreach(data('story.stages') as $branch => $stage)
    {
        $position = strpos(",{$stageList},", ",{$stage},");
        if($position !== false && $position > $minStagePos)
        {
            $minStage    = $stage;
            $minStagePos = $position;
        }
    }
}

$files = data('story.files') ?: null;
if(!empty($files))
{
    foreach($files as $fileInfo) $fileInfo->extra = data('story.version');
}

if($storyType == 'story')
{
    unset($fieldData['stage']['options']['delivered']);
    unset($fieldData['stage']['options']['delivering']);
}

$fields = defineFieldList('story.edit');

$fields->field('title')
    ->position('info')
    ->required(true)
    ->readonly(empty($canEditContent))
    ->control('colorInput', array('colorValue' => data('story.color')))
    ->value(!empty($fieldData['title']['default']) ? $fieldData['title']['default'] : '');

if(data('story.status') != 'reviewing')
{
    $fields->field('reviewer')
        ->position('info')
        ->hidden(empty($canEditContent))
        ->required(true)
        ->control(function() use($fieldData, $forceReview)
        {
            global $lang;
            return inputGroup
            (
                data('story.status') != 'reviewing' ? picker
                (
                    setID('reviewer'),
                    set::name('reviewer[]'),
                    set::items($fieldData['reviewer']['options']),
                    set::value($fieldData['reviewer']['default']),
                    set::multiple(true),
                    on::change('changeReviewer')
                ) : null,
                $forceReview ? null : span
                (
                    setClass('input-group-addon'),
                    checkbox
                    (
                        setID('needNotReview'),
                        set::name('needNotReview'),
                        set::checked(empty($fieldData['reviewer']['default'])),
                        set::value(1),
                        set::text($lang->story->needNotReview),
                        on::change('changeNeedNotReview(e.target)')
                    )
                ),
                formHidden('needNotReview', $forceReview ? 0 : 1)
            );
        });
}

$fields->field('spec')->position('info')->label($lang->story->legendSpec);
$fields->field('verify')->position('info');
if(empty($canEditContent))
{
    $fields->field('spec')->control('html')->value(data('story.spec'));
    $fields->field('verify')->control('html')->value(data('story.verify'));
}
else
{
    $fields->field('spec')->control('editor')->value(data('story.spec'));
    $fields->field('verify')->control('editor')->value(data('story.verify'));
}

$fields->field('twins')
    ->position('info')
    ->label($lang->story->changeSyncTip)
    ->hidden(empty(data('twins')))
    ->control(function()
    {
        return h::ul
        (
            array_values(array_map(function($twin)
            {
                global $lang;
                $branches   = data('branches')   ?: array();
                $branch     = isset($branches[$twin->branch]) ? $branches[$twin->branch] : '';
                $stage      = $lang->story->stageList[$twin->stage];
                $labelClass = data('story.branch') == $twin->branch ? 'primary' : '';

                return h::li
                (
                    setClass('twins'),
                    $branch ? label(setClass($labelClass . ' circle branch size-sm'), set::title($branch), $branch) : null,
                    label(setClass('circle size-sm'), $twin->id),
                    common::hasPriv('story', 'view') ? a(set::href(createLink('story', 'view', "id={$twin->id}")), setClass('title'), set::title($twin->title), setData(array('toggle' => 'modal')), $twin->title) : span(setClass('title'), $twin->title),
                    label(setClass('size-sm'), set::title($stage), $stage),
                    common::hasPriv('story', 'relieved') ? a(set::title($lang->story->relievedTwins), setClass("relievedTwins unlink size-xs"), on::click('unlinkTwins'), setData(array('id' => $twin->id)), icon('unlink')) : null
                );
            }, data('twins')))
        );
    });

$fields->field('files')
    ->position('info')
    ->id('files')
    ->label($lang->story->legendAttach)
    ->hidden(empty($canEditContent))
    ->control(array('control' => 'fileSelector', 'defaultFiles' => $files, 'extra' => data('story.version')))
    ->value('');

$fields->field('product')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->hidden(!empty(data('product.shadow')))
    ->control(function()
    {
        $fieldData = data('fields') ?: array();
        return inputGroup
        (
            on::change('[name=branch]')->call('loadBranch'),
            picker
            (
                setID('product'),
                set::name('product'),
                set::items(!empty($fieldData['product']['options'])  ? $fieldData['product']['options'] : array()),
                set::value(!empty($fieldData['product']['default'])  ? $fieldData['product']['default'] : ''),
                on::change('loadProduct'),
                set::required(true)
            ),
            span
            (
                setClass('branchIdBox'),
                setClass(data('product.type') == 'normal' ? 'hidden' : ''),
                data('product.type') != 'normal' ? picker
                (
                    setID('branch'),
                    set::width('100px'),
                    set::name('branch'),
                    set::items(!empty($fieldData['branch']['options']) ? $fieldData['branch']['options'] : array()),
                    set::value(!empty($fieldData['branch']['default']) ? $fieldData['branch']['default'] : ''),
                ) : null
            )
        );
    });

if(data('story.parent') > 0 && data('product.type') != 'normal')
{
    $fields->field('branch')
        ->position('basic')
        ->group($lang->story->legendBasicInfo)
        ->control('picker')
        ->items(!empty($fieldData['branch']['options']) ? $fieldData['branch']['options'] : array())
        ->value(!empty($fieldData['branch']['default']) ? $fieldData['branch']['default'] : '');
}

$fields->field('module')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->control(array('control' => 'modulePicker', 'manageLink' => createLink('tree', 'browse', "rootID=" . data('story.product') . "&view=story&currentModuleID=0&branch=" . data('story.branch'))))
    ->items(!empty($fieldData['module']['options']) ? $fieldData['module']['options'] : array())
    ->value(!empty($fieldData['module']['default']) ? $fieldData['module']['default'] : '');

$fields->field('parent')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->hidden(!(data('story.parent') >= 0 && (data('showGrade') || data('story.type') != 'epic')) || !empty($story->twins))
    ->control('picker')
    ->items(!empty($fieldData['parent']['options']) ? $fieldData['parent']['options'] : array())
    ->value(!empty($fieldData['parent']['default']) ? $fieldData['parent']['default'] : '');

$fields->field('grade')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->hidden(empty(data('showGrade')))
    ->disabled(data('gradeRule') == 'stepwise')
    ->control('picker')
    ->items(!empty($fieldData['grade']['options']) ? $fieldData['grade']['options'] : array())
    ->value(!empty($fieldData['grade']['default']) ? $fieldData['grade']['default'] : '');

$fields->field('plan')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->hidden(empty($showPlan))
    ->control(function() use($fieldData, $multiplePlan)
    {
        return inputGroup
        (
            span
            (
                setID('planIdBox'),
                picker
                (
                    set::name('plan'),
                    set::items(!empty($fieldData['plan']['options']) ? $fieldData['plan']['options'] : array()),
                    set::value(!empty($fieldData['plan']['default']) ? $fieldData['plan']['default'] : ''),
                    set::multiple(!empty($multiplePlan))
                ),
            ),
            empty($fieldData['plan']['options']) ? btn(set::url(createLink('productplan', 'create', "productID=" . data('story.product') . "&branch=" . data('story.branch'))), setData(array('toggle' => 'modal')), icon('plus')) : null,
            empty($fieldData['plan']['options']) ? btn(set('onclick', "loadProductPlans(" . data('story.product') . ", " . data('story.branch') . ")"), setClass('refresh'), icon('refresh')) : null
        );
    });

$fields->field('source')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->control(array('control' => 'picker', 'data-on' => 'change', 'data-call' => 'toggleFeedback', 'data-params' => 'event'))
    ->items($lang->{$storyType}->sourceList)
    ->value(!empty($fieldData['source']['default']) ? $fieldData['source']['default'] : '');

$fields->field('sourceNote')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->control('input')
    ->value(data('story.sourceNote'));

$fields->field('status')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->disabled(true)
    ->control('picker')
    ->items($lang->story->statusList)
    ->value(data('story.status'));

$fields->field('stage')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->disabled(!empty(data('story.isParent')) || data('story.type') != 'story')
    ->control('picker')
    ->items(!empty($fieldData['stage']['options']) ? $fieldData['stage']['options'] : array())
    ->value(data('story.isParent') == '0' && data('story.type') == 'story' ? $minStage : data('story.stage'));

$fields->field('category')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->control('picker')
    ->items($lang->{$storyType}->categoryList)
    ->value(!empty($fieldData['category']['default']) ? $fieldData['category']['default'] : '');

$fields->field('pri')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->control('picker')
    ->items($lang->{$storyType}->priList)
    ->value(!empty($fieldData['pri']['default']) ? $fieldData['pri']['default'] : '');

$fields->field('estimate')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->disabled(!empty(data('story.isParent')))
    ->control('input')
    ->value($storyEstimate);

$fields->field('feedbackBy')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->hidden(!in_array(data('story.source'), $config->story->feedbackSource))
    ->control('input')
    ->value(data('story.feedbackBy'));

$fields->field('notifyEmail')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->hidden(!in_array(data('story.source'), $config->story->feedbackSource))
    ->control('input')
    ->value(data('story.notifyEmail'));

$fields->field('keywords')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->control('input')
    ->value(data('story.keywords'));

$fields->field('mailto')
    ->position('basic')
    ->group($lang->story->legendBasicInfo)
    ->control(array('control' => 'mailto', 'items' => !empty($fieldData['mailto']['options']) ? $fieldData['mailto']['options'] : array(), 'value' => !empty($fieldData['mailto']['default']) ? $fieldData['mailto']['default'] : ''));

$fields->field('openedBy')
    ->position('basic')
    ->group($lang->story->legendLifeTime)
    ->disabled(true)
    ->control('picker')
    ->items(data('users'))
    ->value(data('story.openedBy'));

$fields->field('assignedTo')
    ->position('basic')
    ->group($lang->story->legendLifeTime)
    ->control('picker')
    ->items($assignedToList)
    ->value(!empty($fieldData['assignedTo']['default']) ? $fieldData['assignedTo']['default'] : '');

if(data('story.status') == 'reviewing')
{
    $fields->field('reviewer')
        ->position('basic')
        ->group($lang->story->legendLifeTime)
        ->label($lang->story->reviewers)
        ->control(array('control' => 'picker', 'data-on' => 'change', 'data-call' => 'changeReviewer', 'multiple' => true))
        ->items(!empty($fieldData['reviewer']['options']) ? $fieldData['reviewer']['options'] : array())
        ->value(!empty($fieldData['reviewer']['default']) ? $fieldData['reviewer']['default'] : '');
}

$fields->field('closedBy')
    ->position('basic')
    ->group($lang->story->legendLifeTime)
    ->hidden(data('story.status') != 'closed')
    ->control('picker')
    ->items(!empty($fieldData['closedBy']['options']) ? $fieldData['closedBy']['options'] : array())
    ->value(!empty($fieldData['closedBy']['default']) ? $fieldData['closedBy']['default'] : '');

$fields->field('closedReason')
    ->position('basic')
    ->group($lang->story->legendLifeTime)
    ->hidden(data('story.status') != 'closed')
    ->control(array('control' => 'picker', 'data-on' => 'change', 'data-call' => 'setStory', 'data-params' => 'event'))
    ->items(!empty($fieldData['closedReason']['options']) ? $fieldData['closedReason']['options'] : array())
    ->value(!empty($fieldData['closedReason']['default']) ? $fieldData['closedReason']['default'] : '');

$fields->field('duplicateStory')
    ->position('basic')
    ->group($lang->story->legendMisc)
    ->hidden(data('story.status') != 'closed')
    ->control(array('control' => 'picker', 'placeholder' => $lang->bug->placeholder->duplicate))
    ->items(!empty($fieldData['duplicateStory']['options']) ? $fieldData['duplicateStory']['options'] : array())
    ->value(!empty($fieldData['duplicateStory']['default']) ? $fieldData['duplicateStory']['default'] : '');
