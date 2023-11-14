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

include './taskteam.html.php';

/* ====== Preparing and processing page data ====== */
jsVar('oldStoryID', $task->story);
jsVar('oldAssignedTo', $task->assignedTo);
jsVar('oldExecutionID', $task->execution);
jsVar('oldConsumed', $task->consumed);
jsVar('taskStatus', $task->status);
jsVar('currentUser', $app->user->account);
jsVar('team', array_values($task->members));
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
$hiddenTeam             = $task->mode != '' ? '' : 'hidden';

/* ====== Define the page structure with zin widgets ====== */

detailHeader
(
    to::prefix(null),
    to::title
    (
        entityLabel
        (
            set::entityID($task->id),
            set::level(1),
            set::text($task->name),
            set::reverse(true)
        )
    )
);

detailBody
(
    set::isForm(true),
    sectionList
    (
        section
        (
            set::title($lang->task->name),
            input
            (
                set::label($lang->task->name),
                set::name('name'),
                set::value($task->name),
                set::placeholder($lang->task->name),
                set::control('input'),
                set::required(true),
                set::autofocus(true)
            )
        ),
        section
        (
            set::title($lang->task->desc),
            editor
            (
                set::name('desc'),
                $task->desc && isHTML($task->desc) ? html($task->desc) : $task->desc
            )
        ),
        section
        (
            set::title($lang->task->story),
            formGroup
            (
                set::width('1/2'),
                control
                (
                    set::type('picker'),
                    set::name('story'),
                    set::value($task->story),
                    set::items($storyOptions)
                )
            )
        ),
        section
        (
            set::title($lang->files),
            upload()
        ),
        formHidden('lastEditedDate', helper::isZeroDate($task->lastEditedDate) ? '' : $task->lastEditedDate)
    ),
    history(),
    detailSide
    (
        tableData
        (
            set::title($lang->task->legendBasic),
            item
            (
                set::name($lang->task->execution),
                picker
                (
                    set::name('execution'),
                    set::required(true),
                    set::value($task->execution),
                    set::items($executionOptions),
                    on::change('loadAll(this.value)')
                )
            ),
            item
            (
                set::name($lang->task->module),
                inputGroup
                (
                    div
                    (
                        setClass('flex grow'),
                        control(set(array
                        (
                            'name'  => 'module',
                            'value' => $task->module,
                            'type'  => 'picker',
                            'class' => 'w-full',
                            'items' => $moduleOptions
                        )))
                    ),
                    div
                    (
                        checkList
                        (
                            setClass('shrink-0 ml-3'),
                            set::name('showAllModule'),
                            set::items(array('1' => $lang->all)),
                            set::value($showAllModule ? '1' : ''),
                            set::inline(true)
                        )
                    )
                )
            ),
            ($task->parent >= 0 and empty($task->team))
                ? item
                (
                    set::name($lang->task->parent),
                    picker
                    (
                        set::name('parent'),
                        set::value($task->parent),
                        set::items($tasks)
                    )
                )
                : null,
            empty($modeText)
                ? item
                (
                    set::name($lang->task->mode),
                    picker
                    (
                        set::name('mode'),
                        set::value($task->mode),
                        set::items($modeOptions),
                        set::required(true),
                        on::change('changeMode')
                    )
                )
                : item
                (
                    set::name($lang->task->mode),
                    inputGroup
                    (
                        $modeText
                    ),
                    formHidden('mode', $task->mode)
                ),
            item
            (
                set::name($lang->task->assignedTo),
                inputGroup
                (
                    div
                    (
                        setClass('flex grow'),
                        picker
                        (
                            set::name('assignedTo'),
                            set::id('assignedTo'),
                            set::value($task->assignedTo),
                            set::items($assignedToOptions),
                            setClass('w-full'),
                            !empty($task->team) && $task->mode == 'linear' ? set::disabled(true) : null
                        )
                    ),
                    div
                    (
                        btn(set(array
                        (
                            'type' => 'btn',
                            'text' => $lang->task->team,
                            'class' => "input-group-btn team-group $hiddenTeam",
                            'url' => '#modalTeam',
                            'data-toggle' => 'modal'
                        )))
                    )
                )
            ),
            item
            (
                set::name($lang->task->type),
                picker
                (
                    set::name('type'),
                    set::required(true),
                    set::value($task->type),
                    set::items($typeOptions)
                )
            ),
            empty($task->children)
                ? item
                (
                    set::name($lang->task->status),
                    picker
                    (
                        set::name('status'),
                        set::required(true),
                        set::value($task->status),
                        set::items($statusOptions)
                    )
                )
                : null,
            item
            (
                set::name($lang->task->pri),
                picker
                (
                    set::name('pri'),
                    set::value($task->pri),
                    set::items($priOptions)
                )
            ),
            item
            (
                set::name($lang->task->mailto),
                inputGroup
                (
                    div
                    (
                        setStyle('width', '70%'),
                        control(set(array
                        (
                            'name' => 'mailto[]',
                            'id' => 'mailto',
                            'value' => $task->mailto,
                            'type' => 'picker',
                            'items' => $mailtoOptions,
                            'multiple' => true
                        )))
                    ),
                    div
                    (
                        setStyle('width', '30%'),
                        control
                        (
                            set::name('contactListMenu'),
                            set::type('picker'),
                            set::items($contactListMenuOptions),
                            on::change('setMailto')
                        )
                    )
                )
            )
        ),
        modalTrigger
        (
            modal
            (
                set::id('modalTeam'),
                set::title($lang->task->teamMember),
                h::table
                (
                    set::id('teamTable'),
                    setClass('table table-form'),
                    $teamForm,
                    h::tr
                    (
                        h::td
                        (
                            setClass('team-saveBtn'),
                            set(array('colspan' => 6)),
                            btn
                            (
                                setClass('toolbar-item btn primary'),
                                $lang->save
                            )
                        )
                    )
                )
            )
        ),
        tableData
        (
            set::title($lang->task->legendEffort),
            item
            (
                set::name($lang->task->estStarted),
                datePicker
                (
                    set::name('estStarted'),
                    helper::isZeroDate($task->estStarted) ? null : set::value($task->estStarted)
                )
            ),
            item
            (
                set::name($lang->task->deadline),
                datePicker
                (
                    set::name('deadline'),
                    helper::isZeroDate($task->deadline) ? null : set::value($task->deadline)
                )
            ),
            item
            (
                set::name($lang->task->estimate),
                inputGroup
                (
                    input
                    (
                        set::name('estimate'),
                        set::value($task->estimate),
                        !empty($task->team) ? set::readonly(true) : null
                    ),
                    div
                    (
                        setClass('input-group-btn'),
                        btn
                        (
                            setClass('btn btn-default'),
                            'H'
                        )
                    )
                )
            ),
            item
            (
                set::name($lang->task->consumed),
                row
                (
                    span
                    (
                        setClass('span-text mr-1'),
                        set::id('consumedSpan'),
                        $task->consumed . 'H'
                    ),
                    h::a
                    (
                        setClass('span-text'),
                        set::href(inlink('recordWorkhour', "id={$task->id}")),
                        set('data-toggle', 'modal'),
                        icon('time')
                    ),
                    formHidden('consumed', $task->consumed)
                )
            ),
            item
            (
                set::name($lang->task->left),
                inputGroup
                (
                    input
                    (
                        set::name('left'),
                        set::value($task->left),
                        !empty($task->team) ? set::readonly(true) : null
                    ),
                    div
                    (
                        setClass('input-group-btn'),
                        btn
                        (
                            setClass('btn btn-default'),
                            'H'
                        )
                    )
                )
            )
        ),
        tableData
        (
            set::title($lang->task->legendLife),
            item
            (
                set::name($lang->task->realStarted),
                datePicker
                (
                    set::name('realStarted'),
                    set::value(helper::isZeroDate($task->realStarted) ? '' : $task->realStarted)
                )
            ),
            item
            (
                set::name($lang->task->finishedBy),
                control
                (
                    set::name('finishedBy'),
                    set::value($task->finishedBy),
                    set::type('picker'),
                    set::items($finishedByOptions)
                )
            ),
            item
            (
                set::name($lang->task->finishedDate),
                datePicker
                (
                    set::name('finishedDate'),
                    set::value(helper::isZeroDate($task->finishedDate) ? '' : $task->finishedDate)
                )
            ),
            item
            (
                set::name($lang->task->canceledBy),
                control
                (
                    set::name('canceledBy'),
                    set::value($task->canceledBy),
                    set::type('picker'),
                    set::items($canceledByOptions)
                )
            ),
            item
            (
                set::name($lang->task->canceledDate),
                datePicker
                (
                    set::name('canceledDate'),
                    set::value(helper::isZeroDate($task->canceledDate) ? '' : $task->canceledDate)
                )
            ),
            item
            (
                set::name($lang->task->closedBy),
                control
                (
                    set::name('closedBy'),
                    set::value($task->closedBy),
                    set::type('picker'),
                    set::items($closedByOptions)
                )
            ),
            item
            (
                set::name($lang->task->closedReason),
                control
                (
                    set::name('closedReason'),
                    set::value($task->closedReason),
                    set::type('picker'),
                    set::items($closedReasonOptions)
                )
            ),
            item
            (
                set::name($lang->task->closedDate),
                datePicker
                (
                    set::name('closedDate'),
                    set::value(helper::isZeroDate($task->closedDate) ? '' : $task->closedDate)
                )
            )
        )
    )
);
