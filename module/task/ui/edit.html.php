<?php
declare(strict_types=1);
/**
 * The edit view of task of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zenggang<zenggang@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
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
jsVar('confirmRecord', $lang->task->confirmRecord);
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
            set::required(true),
            formGroup
            (
                inputControl
                (
                    input
                    (
                        set::name('name'),
                        set::value($task->name),
                        set::placeholder($lang->task->name),
                    ),
                    to::suffix
                    (
                        colorPicker
                        (
                            set::heading($lang->task->colorTag),
                            set::name('color'),
                            set::value($task->color),
                            set::syncColor('#name')
                        )
                    ),
                    set::suffixWidth('35')
                )
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
        $execution->lifetime != 'ops' && !in_array($execution->attribute, array('request', 'review')) ? section
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
        ) : null,
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
            setClass('mt-5'),
            set::title($lang->task->legendBasic),
            item
            (
                set::name($lang->task->execution),
                formGroup
                (
                    picker
                    (
                        set::name('execution'),
                        set::value($task->execution),
                        set::items($executionOptions),
                        on::change('loadAll')
                    )
                )
            ),
            item
            (
                set::name($lang->task->module),
                set::required(strpos(",{$this->config->task->edit->requiredFields},", ",module,") !== false),
                formGroup
                (
                    inputGroup
                    (
                        div
                        (
                            setClass('flex grow'),
                            picker
                            (
                                setClass('w-full'),
                                set::name('module'),
                                set::value($task->module),
                                set::items($moduleOptions),
                                set::width(2/3),
                                set::required(true)
                            )
                        ),
                        div
                        (
                            setClass('flex'),
                            checkbox(
                                setID('showAllModule'),
                                set::rootClass('items-center ml-3'),
                                set::name('showAllModule'),
                                set::text($lang->all),
                                set::value(1),
                                set::checked($showAllModule),
                                on::change('loadAllModule')
                            )
                        )
                    )
                )
            ),
            $task->parent >= 0 && empty($task->team) ? item
            (
                set::name($lang->task->parent),
                picker
                (
                    set::name('parent'),
                    set::value($task->parent),
                    set::items($tasks)
                )
            ) : null,
            empty($modeText) ? item
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
            ) : item
            (
                set::name($lang->task->mode),
                $modeText,
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
                            setID('assignedTo'),
                            setClass('w-full'),
                            set::name('assignedTo'),
                            set::value($task->assignedTo),
                            set::items($assignedToOptions),
                            !empty($task->team) ? set::required(true) : null,
                            !empty($task->team) && $task->mode == 'linear' ? set::disabled(true) : null
                        )
                    ),
                    div
                    (
                        btn
                        (
                            $lang->task->team,
                            setClass('input-group-btn team-group'),
                            set::url('#modalTeam'),
                            setData('toggle', 'modal'),
                            $task->mode == 'multi' ? on::click('disableMembers') : null
                        )
                    )
                )
            ),
            item
            (
                set::name($lang->task->type),
                picker
                (
                    set::name('type'),
                    set::value($task->type),
                    set::items($typeOptions),
                    set::required(true)
                )
            ),
            empty($task->children) ? item
            (
                set::name($lang->task->status),
                picker
                (
                    set::name('status'),
                    set::value($task->status),
                    set::items($statusOptions),
                    set::required(true)
                )
            ) : null,
            item
            (
                set::name($lang->task->pri),
                set::required(strpos(",{$this->config->task->edit->requiredFields},", ",pri,") !== false),
                formGroup
                (
                    priPicker
                    (
                        set::name('pri'),
                        set::value($task->pri),
                        set::items($priOptions),
                    )
                )
            ),
            item
            (
                set::name($lang->task->progress),
                progresscircle
                (
                    set::percent($task->progress),
                    set::circleColor('var(--color-success-500)'),
                    set::circleBg('var(--color-border)'),
                    set::circleWidth(1)
                )
            ),
            item
            (
                set::name($lang->task->mailto),
                inputGroup
                (
                    picker
                    (
                        setID('mailto'),
                        set::name('mailto[]'),
                        set::value($task->mailto),
                        set::items($mailtoOptions),
                        set::multiple(true)
                    ),
                    span
                    (
                        setID('contactBox'),
                        setClass('input-group-addon'),
                        $contactListMenuOptions ? setStyle(array('width' => '100px', 'padding' => '0')) : null,
                        $contactListMenuOptions ? picker
                        (
                            setClass('w-20'),
                            set::name('contactListMenuOptionsMenu'),
                            set::items($contactListMenuOptions),
                            set::placeholder($lang->contact->common)
                        ) :
                        span
                        (
                            setClass('input-group-addon'),
                            a
                            (
                                set('href', createLink('my', 'managecontacts', 'listID=0&mode=new')),
                                set('title', $lang->user->contacts->manage),
                                setClass('mr-2'),
                                setData(array('toggle' => 'modal')),
                                icon('cog'),
                            ),
                            a
                            (
                                setID('refreshMailto'),
                                setClass('text-black'),
                                set('href', 'javascript:void(0)'),
                                icon('refresh')
                            )
                        )
                    )
                )
            )
        ),
        modalTrigger
        (
            modal
            (
                setID('modalTeam'),
                set::title($lang->task->teamMember),
                h::table
                (
                    setID('teamTable'),
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
            setClass('mt-4'),
            set::title($lang->task->legendEffort),
            item
            (
                set::name($lang->task->estStarted),
                set::required(strpos(",{$this->config->task->edit->requiredFields},", ",estStarted,") !== false),
                formGroup
                (
                    datePicker
                    (
                        set::name('estStarted'),
                        helper::isZeroDate($task->estStarted) ? null : set::value($task->estStarted)
                    )
                )
            ),
            item
            (
                set::name($lang->task->deadline),
                set::required(strpos(",{$this->config->task->edit->requiredFields},", ",deadline,") !== false),
                formGroup
                (
                    datePicker
                    (
                        set::name('deadline'),
                        helper::isZeroDate($task->deadline) ? null : set::value($task->deadline)
                    )
                )
            ),
            item
            (
                set::name($lang->task->estimate),
                set::required(strpos(",{$this->config->task->edit->requiredFields},", ",estimate,") !== false),
                formGroup
                (
                    inputControl
                    (
                        input
                        (
                            set::name('estimate'),
                            set::value($task->estimate),
                            !empty($task->team) ? set::readonly(true) : null
                        ),
                        to::suffix($lang->task->suffixHour),
                        set::suffixWidth(20)
                    )
                )
            ),
            item
            (
                set::name($lang->task->consumed),
                inputGroup
                (
                    setClass('items-center'),
                    span
                    (
                        setClass('span-text'),
                        setID('consumedSpan'),
                        $task->consumed . $lang->task->suffixHour
                    ),
                    btn
                    (
                        setClass('ghost text-primary'),
                        icon('time'),
                        set::href(inlink('recordWorkhour', "id={$task->id}")),
                        setData('toggle', 'modal')
                    ),
                    formHidden('consumed', $task->consumed)
                )
            ),
            item
            (
                set::name($lang->task->left),
                formGroup
                (
                    inputControl
                    (
                        input
                        (
                            set::name('left'),
                            set::value($task->left),
                            !empty($task->team) ? set::readonly(true) : null
                        ),
                        to::suffix($lang->task->suffixHour),
                        set::suffixWidth(20)
                    )
                )
            )
        ),
        tableData
        (
            setClass('mt-4'),
            set::title($lang->task->legendLife),
            item
            (
                set::name($lang->task->realStarted),
                formGroup
                (
                    datetimePicker
                    (
                        set::name('realStarted'),
                        set::value(helper::isZeroDate($task->realStarted) ? '' : $task->realStarted)
                    )
                )
            ),
            item
            (
                set::name($lang->task->finishedBy),
                formGroup
                (
                    picker
                    (
                        set::name('finishedBy'),
                        set::value($task->finishedBy),
                        set::items($finishedByOptions)
                    )
                )
            ),
            item
            (
                set::name($lang->task->finishedDate),
                formGroup
                (
                    datetimePicker
                    (
                        set::name('finishedDate'),
                        set::value(helper::isZeroDate($task->finishedDate) ? '' : $task->finishedDate)
                    )
                )
            ),
            item
            (
                set::name($lang->task->canceledBy),
                formGroup
                (
                    picker
                    (
                        set::name('canceledBy'),
                        set::value($task->canceledBy),
                        set::items($canceledByOptions)
                    )
                )
            ),
            item
            (
                set::name($lang->task->canceledDate),
                formGroup
                (
                    datetimePicker
                    (
                        set::name('canceledDate'),
                        set::value(helper::isZeroDate($task->canceledDate) ? '' : $task->canceledDate)
                    )
                )
            ),
            item
            (
                set::name($lang->task->closedBy),
                formGroup
                (
                    picker
                    (
                        set::name('closedBy'),
                        set::value($task->closedBy),
                        set::items($closedByOptions)
                    )
                )
            ),
            item
            (
                set::name($lang->task->closedReason),
                formGroup
                (
                    picker
                    (
                        set::name('closedReason'),
                        set::value($task->closedReason),
                        set::items($closedReasonOptions)
                    )
                )
            ),
            item
            (
                set::name($lang->task->closedDate),
                formGroup
                (
                    datetimePicker
                    (
                        set::name('closedDate'),
                        set::value(helper::isZeroDate($task->closedDate) ? '' : $task->closedDate)
                    )
                )
            )
        )
    )
);
