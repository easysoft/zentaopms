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

$requiredFields = array();
foreach(explode(',', $config->task->create->requiredFields) as $field)
{
    if($field) $requiredFields[$field] = '';
    if($field && strpos($showFields, $field) === false) $showFields .= ',' . $field;
}
$hiddenStory      = (strpos(",$showFields,", ',story,') !== false and $features['story']) ? '' : 'hidden';
$hiddenPri        = strpos(",$showFields,", ',pri,') !== false ? '' : 'hidden';
$hiddenEstimate   = strpos(",$showFields,", ',estimate,') !== false ? '' : 'hidden';
$hiddenEstStarted = strpos(",$showFields,", ',estStarted,') === false ? 'hidden' : '';
$hiddenDeadline   = strpos(",$showFields,", ',deadline,')   === false ? 'hidden' : '';
$hiddenDatePlan   = (!$hiddenEstStarted || !$hiddenDeadline) ? '' : 'hidden';
$hiddenMailto     = strpos(",$showFields,", ',mailto,') !== false ? '' : 'hidden';

/* zin: Set variables to define picker options for form. */
jsVar('showFields', $showFields);
jsVar('toTaskList', !empty($task->id));
jsVar('blockID', $blockID);
jsVar('taskID', $taskID ?? 0);
jsVar('task', $task);
jsVar('ditto', $lang->task->ditto);
jsVar('teamMemberError', $lang->task->error->teamMember);
jsVar('vision', $config->vision);
jsVar('requiredFields', $config->task->create->requiredFields);
jsVar('estimateNotEmpty', sprintf($lang->error->gt, $lang->task->estimate, '0'));
jsVar('window.executionID', $execution->id);
jsVar('window.lifetime', $execution->lifetime);
jsVar('window.attribute', $execution->attribute);
jsVar('window.lifetimeList', $lifetimeList);
jsVar('window.attributeList', $attributeList);
jsVar('hasProduct', $execution->hasProduct);
jsVar('hideStory', $hideStory);

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
    $kanbanRow = formRow(
        formGroup
        (
            set::width('1/2'),
            set::label($lang->kanbancard->region),
            picker
            (
                set::name('region'),
                set::value($regionID),
                set::items($regionPairs),
                on::change('loadLanes'),
                set::required(true)
            )
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->kanbancard->lane),
            picker
            (
                set::name('lane'),
                set::value($laneID),
                set::items($lanePairs),
                set::required(true)
            )
        )
    );
}

/* Set the tip when there is no story. */
if(!empty($execution->hasProduct))
{
    $storyEmptyPreTip = span
    (
        setClass('input-control-prefix'),
        span($lang->task->noticeLinkStory),
        a
        (
            set::href($this->createLink('execution', 'linkStory', "executionID=$execution->id")),
            setClass('text-primary'),
            on::click('closeModal'),
            $lang->execution->linkStory
        )
    );
}
else
{
    $storyEmptyPreTip = span
    (
        setClass('input-control-prefix'),
        span($lang->task->noticeLinkStoryNoProduct)
    );
}
$storyPreviewBtn = span
(
    setClass('input-group-btn flex hidden'),
    setID('preview'),
    modalTrigger
    (
        to::trigger(btn(setClass('text-gray'), set::icon('eye')))
    )
);

$teamForm = array();
if(empty($task->team))
{
    for($i = 1; $i <= 3; $i ++)
    {
        $teamForm[] = h::tr
            (
                h::td
                (
                    setClass('team-index'),
                    span
                    (
                        setClass("team-number"),
                        $i
                    ),
                    icon('angle-down')
                ),
                h::td
                (
                    set::width('240px'),
                    picker
                    (
                        set::name("team[]"),
                        set::items($members)
                    )
                ),
                h::td
                (
                    set::width('135px'),
                    inputControl
                    (
                        input
                        (
                            set::name("teamEstimate[]"),
                            set::placeholder($lang->task->estimateAB)
                        ),
                        to::suffix($lang->task->suffixHour),
                        set::suffixWidth(20)
                    )
                ),
                h::td
                (
                    set::width('100px'),
                    setClass('center'),
                    btnGroup
                    (
                        set::items(array(
                            array('icon' => 'plus',  'class' => 'btn ghost btn-add'),
                            array('icon' => 'trash', 'class' => 'btn ghost btn-delete')
                        ))
                    )
                )
            );
    }
}
else
{
    $i = 0;
    foreach($task->team as $member)
    {
        $i ++;
        $teamForm[] = h::tr
            (
                h::td
                (
                    setClass('team-index'),
                    span
                    (
                        setClass("team-number"),
                        $i
                    ),
                    icon('angle-down')
                ),
                h::td
                (
                    set::width('240px'),
                    picker
                    (
                        set::name("team[]"),
                        set::items($members),
                        set::value($member->account)
                    )
                ),
                h::td
                (
                    set::width('135px'),
                    inputControl
                    (
                        input
                        (
                            set::name("teamEstimate[]"),
                            set::placeholder($lang->task->estimateAB),
                            set::value($member->estimate)
                        ),
                        to::suffix($lang->task->suffixHour),
                        set::suffixWidth(20)
                    )
                ),
                h::td
                (
                    set::width('100px'),
                    setClass('center'),
                    btnGroup
                    (
                        set::items(array(
                            array('icon' => 'plus',  'class' => 'btn ghost btn-add'),
                            array('icon' => 'trash', 'class' => 'btn ghost btn-delete')
                        ))
                    )
                )
            );
    }
}

$selectStoryRow = '';
if($execution->lifetime != 'ops' and !in_array($execution->attribute, array('request', 'review')))
{
    $selectStoryRow = formRow(setID('testStoryBox'), setClass('hidden'));
}

$afterCreateRow = '';
/* Ct redirect within pop-ups. */
if(!isAjaxRequest('modal'))
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
        )
    );
}

/* ====== Define the page structure with zin widgets ====== */

formPanel
(
    setID('taskCreateForm'),
    set::title($lang->task->create),
    $from == 'task' ? set::customFields(true) : null,
    modal
    (
        setID('modalTeam'),
        set::title($lang->task->teamMember),
        h::table
        (
            setID('teamTable'),
            h::tr
            (
                h::td
                (
                    width('90px'),
                    $lang->task->mode
                ),
                h::td
                (
                    picker
                    (
                        set::name("mode"),
                        !empty($task->mode) ? set::value($task->mode) : set::value("linear"),
                        set::items($lang->task->modeList),
                        set::required(true)
                    )
                )
            ),
            setClass('table table-form'),
            $teamForm,
            h::tr
            (
                h::td
                (
                    setClass('team-saveBtn'),
                    set(array('colspan' => 4)),
                    btn
                    (
                        setClass('toolbar-item btn primary'),
                        $lang->save
                    )
                )
            )
        )
    ),
    formRow
    (
        $executionBox ? $executionBox : formHidden('execution', $execution->id),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->task->module),
            set::required(strpos(",{$this->config->task->create->requiredFields},", ",module,") !== false),
            inputGroup
            (
                picker
                (
                    set::name('module'),
                    set::value($task->module),
                    set::items($modulePairs),
                    on::change('loadExecutionStories')
                ),
                span
                (
                    setClass('input-group-btn'),
                    btn
                    (
                        setID('showAllModuleButton'),
                        $lang->task->allModule,
                        on::click('showAllModule')
                    ),
                    formHidden('isShowAllModule', 0)
                )
            )
        )
    ),
    $kanbanRow,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->task->type),
            set::required(true),
            picker
            (
                set::name('type'),
                set::items($lang->task->typeList),
                set::value($task->type),
                set::required(true)
            ),
            on::change('typeChange')
        ),
        formGroup
        (
            set::width('1/2'),
            setID('selectTestStoryBox'),
            setClass('hidden items-center'),
            checkbox(
                setID('selectTestStory'),
                set::name('selectTestStory'),
                set::value(1),
                set::text($lang->task->selectTestStory),
                set::rootClass('ml-4'),
                on::change('toggleSelectTestStory')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->task->assignTo),
            setClass('assignedToBox'),
            picker
            (
                setID('assignedTo'),
                set::name('assignedTo'),
                set::value($task->assignedTo),
                set::items($members)
            ),
            btn
            (
                set
                (
                    array
                    (
                        'class' => 'btn primary-pale hidden add-team mr-3',
                        'data-toggle' => 'modal',
                        'url' => '#modalTeam',
                        'icon' => 'plus'
                    )
                ),
                $lang->task->addMember
            ),
            div(setClass('assignedToList'))
        ),
        formGroup
        (
            set::width('1/10'),
            setID('multipleBox'),
            setClass('items-center'),
            checkbox(
                set::name('multiple'),
                set::text($lang->task->multiple),
                set::rootClass('ml-4'),
                on::change('toggleTeam')
            )
        )
    ),
    formRow
    (
        setClass('hidden'),
        formGroup
        (
            set::control('hidden'),
            set::name('teamMember')
        )
    ),
    formRow
    (
        setClass($hiddenStory || $hideStory ? 'hidden' : ''),
        formGroup
        (
            set::label($lang->task->story),
            setClass(empty($stories) ? 'hidden' : ''),
            set::required(strpos(",{$this->config->task->create->requiredFields},", ",story,") !== false),
            inputGroup
            (
                picker
                (
                    setID('story'),
                    set::name('story'),
                    set::value($task->story),
                    set::items(array_filter($stories)),
                    on::change('setStoryRelated')
                ),
                $storyPreviewBtn
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
                    set('readonly'),
                    set('onfocus', 'this.blur()')
                ),
                span
                (
                    setClass('input-control-suffix'),
                    btn(
                        setClass('text-gray'),
                        setID('refreshStories'),
                        set::icon('refresh'),
                        on::click('loadExecutionStories')
                    )
                )
            )
        )
    ),
    $selectStoryRow,
    formRow
    (
        formGroup
        (
            set::width('4/5'),
            set::label($lang->task->name),
            set::strong(true),
            set::required(true),
            inputControl
            (
                input
                (
                    set::name('name'),
                    set::value($task->name)
                ),
                to::suffix
                (
                    colorPicker
                    (
                        set::heading($lang->task->colorTag),
                        set::name('color'),
                        set::value($task->color),
                        set::syncColor('#name')
                    ),
                    checkbox(
                        setID('copyButton'),
                        set::name('copyButton'),
                        set::value(1),
                        set::text($lang->task->copyStoryTitle),
                        set::rootClass('ml-8 border-l border-gray pl-2'),
                        on::change('copyStoryTitle')
                    )
                )
            ),
            formHidden('storyEstimate', ''),
            formHidden('storyDesc', ''),
            formHidden('storyPri', 0)
        ),
        formGroup
        (
            set::width('1/5'),
            setClass('no-background'),
            inputGroup
            (
                span
                (
                    setClass("input-group-addon {$hiddenPri}"),
                    $lang->task->pri
                ),
                priPicker
                (
                    setClass($hiddenPri),
                    set::name('pri'),
                    set::value('3'),
                    set::items(array_filter($lang->task->priList))
                ),
                span
                (
                    setClass("input-group-addon {$hiddenEstimate}"),
                    $lang->task->estimateAB
                ),
                inputControl
                (
                    setClass($hiddenEstimate),
                    input(set::name('estimate')),
                    to::suffix($lang->task->suffixHour),
                    set::suffixWidth(20)
                )
            )
        )
    ),
    formGroup
    (
        set::label($lang->task->desc),
        set::control('editor'),
        set::name('desc'),
        set::rows('5')
    ),
    formGroup
    (
        set::label($lang->story->files),
        upload()
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->task->datePlan),
        setClass($hiddenDatePlan),
        set::required(strpos(",{$this->config->task->create->requiredFields},", ",estStarted,") !== false || strpos(",{$this->config->task->create->requiredFields},", ",deadline,") !== false),
        inputGroup
        (
            datepicker
            (
                setClass($hiddenEstStarted),
                set::control('date'),
                set::name('estStarted'),
                set::value($task->estStarted),
                set::placeholder($lang->task->estStarted)
            ),
            span
            (
                setClass('input-group-addon'),
                setClass($hiddenEstStarted || $hiddenDeadline ? 'hidden' : ''),
                $lang->task->to
            ),
            datepicker
            (
                setClass($hiddenDeadline),
                set::control('date'),
                set::name('deadline'),
                set::value($task->deadline),
                set::placeholder($lang->task->deadline)
            )
        )
    ),
    formGroup
    (
        setClass($hiddenMailto),
        set::label($lang->product->mailto),
        mailto(set::items($users))
    ),
    $afterRow,
);
