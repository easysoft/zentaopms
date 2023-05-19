<?php
declare(strict_types=1);
/**
 * The create view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tian Shujie<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

/* ====== Preparing and processing page data ====== */

/* zin: Set variables to define picker options for form. */
jsVar('toTaskList', !empty($task->id));
jsVar('blockID', $blockID);
jsVar('executionID', $execution->id);
jsVar('ditto', $lang->task->ditto);
jsVar('teamMemberError', $lang->task->error->teamMember);
jsVar('vision', $config->vision);
jsVar('requiredFields', $config->task->create->requiredFields);
jsVar('estimateNotEmpty', sprintf($lang->error->gt, $lang->task->estimate, '0'));
jsVar('window.lifetime', $execution->lifetime);
jsVar('window.attribute', $execution->attribute);
jsVar('window.lifetimeList', $lifetimeList);
jsVar('window.attributeList', $attributeList);
jsVar('hasProduct', $execution->hasProduct);
jsVar('hideStory', $hideStory);
$requiredFields = array();
foreach(explode(',', $config->task->create->requiredFields) as $field)
{
    if($field) $requiredFields[$field] = '';
    if($field && strpos($showFields, $field) === false) $showFields .= ',' . $field;
}
jsVar('showFields', $showFields);

$executionBox = '';
/* Cannot show execution field in kanban. */
if($execution->type != 'kanban' or $this->config->vision == 'lite')
{
    $executionBox = formGroup
    (
        set::width('1/2'),
        set::name('execution'),
        set::label($lang->task->execution),
        set::value($execution->id),
        set::items($executions),
        on::change('loadAll')
    );
}

$kanbanRow = '';
/* The region and lane fields are only showed in kanban. */
if($execution->type == 'kanban')
{
    $kanbanRow = formRow (
        formGroup
        (
            set::width('1/2'),
            set::name('region'),
            set::label($lang->kanbancard->region),
            set::value($regionID),
            set::items($regionPairs),
            on::change('loadLanes')
        ),
        formGroup
        (
            set::width('1/2'),
            set::name('lane'),
            set::label($lang->kanbancard->lane),
            set::value($laneID),
            set::items($lanePairs),
        ),
    );
}

/* Set the tip when there is no story. */
if(!empty($execution->hasProduct))
{
    $storyEmptyPreTip = span
    (
        setClass('input-control-prefix'),
        span
        (
            $lang->task->noticeLinkStory,
        ),
        a
        (
            set::href($this->createLink('execution', 'linkStory', "executionID=$execution->id")),
            setClass('text-primary'),
            $lang->execution->linkStory
        ),
    );
}
else
{
    $storyEmptyPreTip = span
    (
        setClass('input-control-prefix'),
        span
        (
            $lang->task->noticeLinkStory,
        ),
    );
}
$storyPreviewBtn = span
(
    setClass('input-group-btn flex hidden'),
    set::id('preview'),
    modalTrigger
    (
        to::trigger(
            btn(
                setClass('text-gray'),
                set::icon('eye'),
            ),
        ),
        set::url('')
    ),
);

$afterCreateRow = '';
/* Ct redirect within pop-ups. */
if(!isonlybody())
{
    $afterRow = formGroup
    (
        set::width('3/4'),
        set::label($lang->task->afterSubmit),
        radioList
        (
            set::name('after'),
            set::value(!empty($task->id) ? 'toTaskList' : 'continueAdding'),
            set::items($config->task->afterOptions),
            set::inline(true)
        ),
    );
}

/* ====== Define the page structure with zin widgets ====== */

formPanel
(
    set::id('taskCreateForm'),
    set::title($lang->task->create),
    formRow
    (
        $executionBox,
        formGroup
        (
            set::width('1/2'),
            set::name('module'),
            set::label($lang->task->module),
            set::value($task->module),
            set::items($modulePairs)
        )
    ),
    $kanbanRow,
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::name('type'),
            set::label($lang->task->type),
            set::control('select'),
            set::items($lang->task->typeList),
            set::value($task->type),
            on::change('typeChange')
        ),
        formGroup
        (
            set::width('1/4'),
            set::id('selectTestStoryBox'),
            setClass('hidden items-center'),
            checkbox(
                set::id('selectTestStory'),
                set::name('selectTestStory'),
                set::text($lang->task->selectTestStory),
                set::rootClass('ml-4'),
                on::change('toggleSelectTestStory'),
            )
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::label($lang->task->assignTo),
            select
            (
                set::id('assignedTo'),
                set::name('assignedTo[]'),
                set::value($task->assignedTo),
                set::items($members),
            ),
        ),
        formGroup
        (
            set::width('1/10'),
            set::id('multipleBox'),
            setClass('items-center'),
            checkbox(
                set::name('multiple'),
                set::text($lang->task->multiple),
                set::rootClass('ml-4'),
                on::change('showTeamBox'),
            )
        ),
        formGroup
        (
            set::width('1/4'),
            set::class('modeBox hidden'),
            radioList(
                set::name('mode'),
                set::value(!empty($task->mode) ? $task->mode : 'linear'),
                set::class('ml-4'),
                set::items($config->task->modeOptions),
                set::inline(true)
            )
        )
    ),
    formRow
    (
        setClass($hideStory ? 'hidden' : ''),
        formGroup
        (
            set::label($lang->task->story),
            setClass(empty($stories) ? 'hidden' : ''),
            inputGroup
            (
                select
                (
                    set::id('story'),
                    set::name('story'),
                    set::value($task->story),
                    set::items($stories),
                    on::change('setStoryRelated'),
                ),
                $storyPreviewBtn,
            )
        ),
        formGroup
        (
            set::label($lang->task->story),
            setClass(!empty($stories) ? 'hidden' : ''),
            div
            (
                setClass('empty-story-tip input-control has-prefix has-suffix'),
                $storyEmptyPreTip,
                input(
                    set::name(''),
                    prop('readonly'),
                    prop('onfocus', 'this.blur()'),
                ),
                span
                (
                    setClass('input-control-suffix'),
                    btn(
                        setClass('text-gray'),
                        set::id('refreshStories'),
                        set::icon('refresh'),
                        on::click('loadExecutionStories'),
                    )
                ),
            ),
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('3/4'),
            set::label($lang->task->name),
            set::name('name'),
            set::value($task->name),
            set::strong(true),
        ),
        formGroup
        (
            set::width('1/4'),
            setClass('no-background'),
            inputGroup
            (
                $lang->task->pri,
                select
                (
                    set::name('pri'),
                    set::items($lang->task->priList),
                ),
                $lang->task->estimate,
                inputControl
                (
                    input(set::name('estimate')),
                    to::suffix($lang->task->suffixHour),
                    set::suffixWidth(20),
                ),
            ),
        ),
    ),
    formGroup
    (
        set::label($lang->task->desc),
        editor
        (
            set::name('desc'),
            set::rows('5'),
        )
    ),
    formGroup
    (
        set::name('files[]'),
        set::label($lang->story->files),
        set::control('file')
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->task->datePlan),
        inputGroup
        (
            input
            (
                set::type('date'),
                set::name('estStarted'),
                set::value($task->estStarted),
                set::placeholder($lang->task->estStarted),
            ),
            $lang->task->to,
            input
            (
                set::type('date'),
                set::name('deadline'),
                set::value($task->deadline),
                set::placeholder($lang->task->deadline),
            ),
        )
    ),
    formGroup
    (
        set::label($lang->product->mailto),
        set::name('mailto[]'),
        set::items($users),
    ),
    $afterRow
);


/* ====== Render page ====== */

$pageType = isonlybody() ? 'modal' : 'page';
render($pageType);
