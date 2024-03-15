<?php
namespace zin;
global $lang,$config;

$fields = defineFieldList('task.create', 'task');

/* Set module field. */
$fields->field('module')
    ->checkbox(array('text' => $lang->task->allModule, 'name' => 'isShowAllModule', 'checked' => data('showAllModule')))
    ->control(array('control' => 'picker', 'required' => true))
    ->items(data('modulePairs'))
    ->value(data('task.module'));

$fields->field('desc')
    ->width('full')
    ->control(array('control' => 'editor', 'templateType' => 'task'));

/* Set foldable attribute. */
$fields->field('module')->foldable();
$fields->field('files')->foldable();
$fields->field('mailto')->foldable();
$fields->field('keywords')->foldable();

/* Set assignedTo field. */
$buildAssginedTo = function($props)
{
    $assignedToListBox = null;
    if(!empty(data('task.team')))
    {
        $i = 1;
        foreach(data('task.team') as $member)
        {
            $users              = data('users');
            $assignedToListBox .= "<div class='picker-multi-selection' data-index={$i}><span class='text'>{$users[$member->account]}</span><div class='picker-deselect-btn btn size-xs ghost'><span class='close'></span></div></div>";
            if($i < count(data('task.team')) && data('task.mode') == 'linear') $assignedToListBox .= '<i class="icon icon-arrow-right"></i>';

            $i ++;
        }
    }

    $hiddenTeamBtn = empty(data('task.mode')) ? 'hidden' : '';
    return div
        (
            setClass('assignedToBox flex border rounded h-8'),
            picker
            (
                setClass($hiddenTeamBtn ? '' : 'hidden'),
                set::name('assignedTo'),
                set::value(data('task.assignedTo')),
                set::items(data('members'))
            ),
            btn
            (
                set
                (
                    array
                    (
                        'class' => "btn primary-pale add-team mr-3 $hiddenTeamBtn",
                        'data-toggle' => 'modal',
                        'url' => '#modalTeam',
                        'icon' => 'plus'
                    )
                ),
                data('lang.task.addMember')
            ),
            div
            (
                setClass('assignedToList'),
                html($assignedToListBox)
            )

        );
};

$fields->field('assignedToBox')
    ->label($lang->task->assignedTo)
    ->checkbox(array('text' => $lang->task->multiple, 'name' => 'multiple', 'checked' => !empty(data('task.mode'))))
    ->control($buildAssginedTo);

/* Set name field width. */
$nameWidth = 'w-1/2';
if(empty(data('features.story')) && data('execution.type') != 'kanban' && !empty(data('execution.multiple'))) $nameWidth .= ' full:w-full';
if(data('execution.type') == 'kanban') $nameWidth .= ' lite:w-full';
$fields->field('name')->className($nameWidth);

if(!empty(data('features.story')) && (data('execution.type') == 'kanban' || empty(data('execution.multiple'))))
{
    $fields->field('module')->wrapBefore();
}

/* Set region and lane fieldList. */
$fields->field('region')
    ->hidden(data('execution.type') != 'kanban')
    ->label(data('lang.kanbancard.region'))
    ->control('picker', array('required' => true))
    ->items(data('regionPairs') ? data('regionPairs') : array())
    ->value(data('regionID'));

$fields->field('lane')
    ->hidden(data('execution.type') != 'kanban')
    ->label(data('lang.kanbancard.lane'))
    ->control('picker', array('required' => true))
    ->items(data('lanePairs') ? data('lanePairs') : array())
    ->value(data('laneID'));

/* Set story field control. */
$buildStoryBox = function($props)
{
    if(!empty(data('execution.hasProduct')))
    {
        $executionID      = data('execution.id');
        $storyEmptyPreTip = span
            (
                setClass('input-control-prefix opacity-100'),
                span(setClass('opacity-50'), data('lang.task.noticeLinkStory')),
                a
                (
                    set::href(createLink('execution', 'linkStory', "executionID={$executionID}")),
                    setClass('text-primary'),
                    isInModal() ? setData('toggle', 'modal') : null,
                    isInModal() ? setData('size', 'lg') : null,
                    data('lang.execution.linkStory')
                )
            );
    }
    else
    {
        $storyEmptyPreTip = span
            (
                setClass('input-control-prefix'),
                span(data('lang.task.noticeLinkStoryNoProduct'))
            );
    }

    return div
        (
            inputGroup
            (
                setClass('setStoryBox'),
                setClass(empty(data('stories')) ? 'hidden' : ''),
                picker
                (
                    set::name('story'),
                    set::value(data('task.story')),
                    set::items(data('stories'))
                ),
                btn
                (
                    setID('preview'),
                    setClass('hidden'),
                    setData('toggle', 'modal'),
                    setData('url', '#'),
                    setData('size', 'lg'),
                    icon('eye text-gray')
                )
            ),
            inputGroup
            (
                setClass('empty-story-tip input-control has-prefix has-suffix'),
                setClass(empty(data('stories')) ? '' : 'hidden'),
                $storyEmptyPreTip,
                input
                (
                    set::name(''),
                    set('readonly'),
                    set('onfocus', 'this.blur()')
                ),
            )
        );
};

$fields->field('storyBox')
    ->required(strpos($config->task->create->requiredFields, 'story') !== false)
    ->label($lang->task->story)
    ->checkbox(array('text' => $lang->task->syncStory, 'name' => 'copyButton'))
    ->hidden(!data('features.story'))
    ->foldable()
    ->control($buildStoryBox);

/* Remove story relate jump link. */
if(!isAjaxRequest('modal'))
{
    $fields->field('after')
        ->label($lang->task->afterSubmit)
        ->width('full')
        ->control(array('control' => 'radioList', 'inline' => true))
        ->value(data('task.id') ? 'continueAdding' : 'toTaskList')
        ->items(empty(data('features.story')) ? array('toTaskList' => $lang->task->afterChoices['toTaskList']) : $config->task->afterOptions);
}

if(!empty(data('features.story'))) $fields->field('type')->checkbox(array('text' => $lang->task->selectTestStory, 'name' => 'selectTestStory'));

/* Set hidden control. */
$fields->field('storyEstimate')
    ->hidden()
    ->control('input');

$fields->field('storyDesc')
    ->hidden()
    ->control('input');

$fields->field('storyPri')
    ->hidden()
    ->control('input')
    ->value('0');

$fields->field('taskName')
    ->hidden()
    ->control('input');

$fields->field('taskEstimate')
    ->hidden()
    ->control('input');

/* Set test story task control. */
$buildTestStoryBox = function($props)
{
    return div(setID('testStoryBox'));
};

$fields->field('testStoryBox')
    ->label($lang->task->selectTestStory)
    ->labelHint($lang->task->selectTestStoryTip)
    ->width('full')
    ->hidden()
    ->control($buildTestStoryBox);
