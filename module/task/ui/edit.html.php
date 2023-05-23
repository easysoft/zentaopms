<?php
declare(strict_types=1);
/**
 * The edit view of task of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zenggang<zenggang@easycorp.ltd>
 * @package     task
 * @link        http://www.zentao.net
 */

namespace zin;

/* ====== Preparing and processing page data ====== */
jsVar('oldStoryID', $task->story);
jsVar('oldAssignedTo', $task->assignedTo);
jsVar('oldExecutionID', $task->execution);
jsVar('oldConsumed', $task->consumed);
jsVar('taskStatus', $task->status);
jsVar('currentUser', $app->user->account);
jsVar('team', $task->members);
jsVar('members', $members);
jsVar('page', 'edit');
jsVar('confirmChangeExecution', $lang->task->confirmChangeExecution);
jsVar('teamMemberError', $lang->task->error->teamMember);
jsVar('totalLeftError', sprintf($this->lang->task->error->leftEmptyAB, $this->lang->task->statusList[$task->status]));
jsVar('estimateNotEmpty', sprintf($lang->error->gt, $lang->task->estimate, '0'));
jsVar('leftNotEmpty', sprintf($lang->error->gt, $lang->task->left, '0'));
jsVar('requiredFields', $config->task->edit->requiredFields);

/* zin: Set variables to define picker options for form */
$formTitle        = $task->name;
$executionOptions = $executions;
$moduleOptions    = $modules;
$storyOptions     = $stories;
if($task->status == 'wait' and $task->parent == 0)
{
    $modeOptions = $lang->task->editModeList;
}
else
{
    $modeText = $task->mode == '' ? $lang->task->editModeList['single'] : zget($lang->task->editModeList, $task->mode);
}
$assignedToOptions      = $taskMembers;
$typeOptions            = $lang->task->typeList;
$statusOptions          = (array)$lang->task->statusList;
$priOptions             = $lang->task->priList;
$mailtoOptions          = $users;
$contactListMenuOptions = $contactLists;
$finishedByOptions      = $members;
$canceledByOptions      = $users;
$closedByOptions        = $users;
$closedReasonOptions    = $lang->task->reasonList;
$teamOptions            = $members;

/* ====== Define the page structure with zin widgets ====== */

/* zin: Define the form in main content */
formPanel
(
    set::title(''),
    entityLabel
    (
        set::entityID($task->id),
        set::level(1),
        set::text($task->name),
    ),
    setStyle(['max-width' => '100%']),
    div
    (
        setClass('flex'),
        cell
        (
            set('width', '2/3'),
            h::strong
            (
                $lang->task->name,
                setClass('leading-8')
            ),
            input
            (
                set::label($lang->task->name),
                set::name('name'),
                set::value($task->name),
                set::placeholder($lang->task->name),
                set::control('input'),
                set::required(true),
                set::autofocus(true),
            ),
            h::hr(setClass('my-5')),
            h::strong
            (
                $lang->task->desc,
                setClass('leading-8')
            ),
            textarea
            (
                set::label($lang->task->desc),
                set::name('desc'),
                set::value(htmlSpecialString($task->desc)),
                set::control('textarea'),
            ),
            h::hr(setClass('my-3')),
            h::strong
            (
                $lang->comment,
                setClass('leading-8')
            ),
            textarea
            (
                set::label($lang->comment),
                set::name('comment'),
                set::value(''),
                set::control('textarea'),
            ),
            h::hr(setClass('my-3')),
            h::strong
            (
                $lang->files,
                setClass('leading-8')
            ),
            h::hr(setClass('my-3')),
            history
            (
                set::actions($actions),
                set::users($users),
                set::methodName($methodName),
            )
        ),
        cell
        (
            set('width', '1/3'),
            setClass('pl-5'),
            h::strong
            (
                $lang->task->legendBasic,
                setClass('leading-8')
            ),
            formGroup
            (
                set::name("execution"),
                set::label($lang->task->execution),
                set::required(true),
                set::value($task->execution),
                set::control("picker"),
                set::strong(true),
                set::items($executionOptions),
                on::change('loadAll(this.value)'),
            ),
            formRow
            (
                formGroup
                (
                    set::label($lang->task->module),
                    set::strong(true),
                    inputGroup
                    (
                        control(set(array
                        (
                            'name' => "module",
                            'value' => $task->module,
                            'type' => "picker",
                            'items' => $moduleOptions
                        ))),
                    )
                ),
                formGroup
                (
                    set::width('1/5'),
                    set::name('showAllModule'),
                    set::value($showAllModule ? '1' : ''),
                    set::control(array('type' => 'checkList', 'inline' => true)),
                    set::items(array('1' => $lang->all))
                )
            ),
            formGroup
            (
                set::name("story"),
                set::label($lang->task->story),
                set::value($task->story),
                set::control("picker"),
                set::strong(true),
                set::items($storyOptions)
            ),
            ($task->parent >= 0 and empty($task->team)) ? formGroup
            (
                set::name("parent"),
                set::label($lang->task->parent),
                set::value($task->parent),
                set::control("picker"),
                set::strong(true),
                set::items($tasks)
            ) : null,
            (empty($modeText)) ? formGroup
            (
                set::name("mode"),
                set::label($lang->task->mode),
                set::value($task->mode),
                set::control("picker"),
                set::strong(true),
                set::items($modeOptions),
                on::change('changeMode(this.value)')
            ) : formGroup
            (
                set::label($lang->task->mode),
                set::strong(true),
                inputGroup
                (
                    $modeText
                )
            ),
            formGroup
            (
                set::label($lang->task->assignedTo),
                set::strong(true),
                inputGroup
                (
                    control(set(array
                    (
                        'name' => "assignedTo",
                        'id' => "assignedTo",
                        'value' => $task->assignedTo,
                        'disabled' => !empty($task->team) && $task->mode == 'linear',
                        'type' => "picker",
                        'items' => $assignedToOptions
                    ))),
                    $task->mode != ''
                    ? btn(set(array
                    (
                        'type' => "btn",
                        'text' => $lang->task->team,
                        'class' => "input-group-btn team-group"
                    )))
                    : null
                )
            ),
            formGroup
            (
                set::name("type"),
                set::label($lang->task->type),
                set::required(true),
                set::value($task->type),
                set::control("picker"),
                set::strong(true),
                set::items($typeOptions)
            ),
            empty($task->children) ? formGroup
            (
                set::name("status"),
                set::label($lang->task->status),
                set::value($task->status),
                set::control("picker"),
                set::strong(true),
                set::items($statusOptions)
            ) : null,
            formGroup
            (
                set::name("pri"),
                set::label($lang->task->pri),
                set::value($task->pri),
                set::control("picker"),
                set::strong(true),
                set::items($priOptions)
            ),
            formGroup
            (
                set::label($lang->task->mailto),
                set::strong(true),
                inputGroup
                (
                    control(set(array
                    (
                        'name' => "mailto[]",
                        'id' => "mailto",
                        'value' => $task->mailto,
                        'type' => "picker",
                        'items' => $mailtoOptions,
                        'multiple' => true
                    ))),
                    control
                    (
                        setStyle('width', '30%'),
                        set::name('contactListMenu'),
                        set::type("picker"),
                        set::items($contactListMenuOptions),
                        on::change('setMailto')
                    ),
                )
            ),
            h::strong
            (
                $lang->task->legendEffort,
                setClass('leading-8')
            ),
            formGroup
            (
                set::name("estStarted"),
                set::label($lang->task->estStarted),
                set::value($task->estStarted),
                set::strong(true),
                set::control("date")
            ),
            formGroup
            (
                set::name("deadline"),
                set::label($lang->task->deadline),
                set::value($task->deadline),
                set::strong(true),
                set::control("date")
            ),
            formGroup
            (
                set::name("estimate"),
                set::label($lang->task->estimate),
                set::value($task->estimate),
                set::strong(true),
                set::control("text")
            ),
            formGroup
            (
                set::label($lang->task->consumed),
                set::strong(true),
                span
                (
                    setClass('span-text'),
                    $task->consumed,
                ),
                h::a
                (
                    setClass('span-text'),
                    icon
                    (
                        'time'
                    )
                )
            ),
            formGroup
            (
                set::name("left"),
                set::label($lang->task->left),
                set::value($task->left),
                set::strong(true),
                set::control("text")
            ),
            h::strong
            (
                $lang->task->legendLife,
                setClass('leading-8')
            ),
            formGroup
            (
                set::name("realStarted"),
                set::label($lang->task->realStarted),
                set::value(helper::isZeroDate($task->realStarted) ? '' : $task->realStarted),
                set::strong(true),
                set::control("datetime")
            ),
            formGroup
            (
                set::name("finishedBy"),
                set::label($lang->task->finishedBy),
                set::value($task->finishedBy),
                set::control("picker"),
                set::strong(true),
                set::items($finishedByOptions)
            ),
            formGroup
            (
                set::name("finishedDate"),
                set::label($lang->task->finishedDate),
                set::value($task->finishedDate),
                set::strong(true),
                set::control("datetime")
            ),
            formGroup
            (
                set::name("canceledBy"),
                set::label($lang->task->canceledBy),
                set::value($task->canceledBy),
                set::control("picker"),
                set::strong(true),
                set::items($canceledByOptions)
            ),
            formGroup
            (
                set::name("canceledDate"),
                set::label($lang->task->canceledDate),
                set::value($task->canceledDate),
                set::strong(true),
                set::control("datetime")
            ),
            formGroup
            (
                set::name("closedBy"),
                set::label($lang->task->closedBy),
                set::value($task->closedBy),
                set::control("picker"),
                set::strong(true),
                set::items($closedByOptions)
            ),
            formGroup
            (
                set::name("closedReason"),
                set::label($lang->task->closedReason),
                set::value($task->closedReason),
                set::control("picker"),
                set::strong(true),
                set::items($closedReasonOptions)
            ),
            formGroup
            (
                set::name("closedDate"),
                set::label($lang->task->closedDate),
                set::value($task->closedDate),
                set::strong(true),
                set::control("datetime")
            ),
            formRow
            (
                set::hidden(true),
                formGroup
                (
                    set::name("lastEditedDate"),
                    set::value($task->lastEditedDate),
                    set::control("input"),
                    set::id("lastEditedDate"),
                )
            ),
            formRow
            (
                set::hidden(true),
                formGroup
                (
                    set::name("team[]"),
                    set::control("picker"),
                    set::id("team"),
                    set::strong(true),
                    set::items($teamOptions)
                ),
                formGroup
                (
                    inputGroup
                    (

                    )
                )
            ),
        )
    )
);

/* ====== Render page ====== */

render();
