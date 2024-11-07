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

include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

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
jsVar('+parentEstStarted', !empty($parentTask) ? $parentTask->estStarted : '');
jsVar('+parentDeadline', !empty($parentTask) ? $parentTask->deadline : '');
jsVar('ignoreLang', $lang->project->ignore);
jsVar('+overParentEstStartedLang', !empty($parentTask) ? sprintf($lang->task->overParentEsStarted, $parentTask->estStarted) : '');
jsVar('+overParentDeadlineLang', !empty($parentTask) ? sprintf($lang->task->overParentDeadline, $parentTask->deadline) : '');

$confirmSyncTip = '';
if(!empty($syncChildren) && !empty($task->children)) $confirmSyncTip = sprintf($lang->task->syncStoryToChildrenTip, 'ID' . implode(', ID', $syncChildren));
jsVar('confirmSyncTip', $confirmSyncTip);
jsVar('taskID', $task->id);
jsVar('taskStory', $task->story);

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
$finishedByOptions      = $members;
$canceledByOptions      = $users;
$closedByOptions        = $users;
$closedReasonOptions    = $lang->task->reasonList;
$teamOptions            = $members;
$hiddenTeam             = $task->mode != '' ? '' : 'hidden';

if(!empty($task->team))
{
    foreach($task->team as $member)
    {
        $member->team           = $member->account;
        $member->teamSource     = $member->account;
        $member->teamEstimate   = (float)$member->estimate;
        $member->teamConsumed   = (float)$member->consumed;
        $member->teamLeft       = (float)$member->left;
        $member->memberDisabled = false;
        $member->memberStatus   = $member->status;
        if($member->memberStatus == 'done') $member->memberDisabled = true;
        if(strpos('|closed|cancel|pause|', $task->status) !== false && $app->rawMethod != 'activate')
        {
            $member->memberStatus   = $task->status;
            $member->memberDisabled = true;
        }

        $member->hourDisabled = $member->memberDisabled;
    }
}

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
    set::formID("taskEditForm{$task->id}"),
    set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
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
        $execution->lifetime != 'ops' ? section
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
                    set::items($storyOptions),
                    on::change('setStoryModule')
                )
            )
        ) : null,
        section
        (
            set::title($lang->files),
            $task->files ? fileList
            (
                set::files($task->files),
                set::fieldset(false),
                set::showEdit(true),
                set::showDelete(true)
            ) : null,
            fileSelector()
        ),
        formHidden('lastEditedDate', helper::isZeroDate($task->lastEditedDate) ? '' : $task->lastEditedDate)
    ),
    history(),
    detailSide
    (
        set::isForm(true),
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
                set::trClass('moduleTR'),
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
                                set::required(true),
                                on::change('loadStories')
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
            $task->parent >= 0 && empty($task->team) && $config->vision != 'lite' ? item
            (
                set::name($lang->task->parent),
                picker
                (
                    set::name('parent'),
                    set::value($task->parent),
                    set::items($tasks),
                    on::change('getParentEstStartedAndDeadline')
                )
            ) : formHidden('parent', $task->parent),
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
                            !empty($task->team) && $task->mode == 'linear' && !in_array($task->status, array('done', 'closed')) ? set::disabled(true) : null,
                            $task->status == 'closed' ? set::disabled(true) : null,
                        )
                    ),
                    div
                    (
                        btn
                        (
                            $lang->task->team,
                            setClass('input-group-btn team-group', empty($task->team) && (!$task->mode) ? 'hidden' : ''),
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
                    on::change()->do('statusChange(target)'),
                    set::name('status'),
                    set::value($task->status),
                    set::items($statusOptions),
                    set::required(true)
                )
            ) : formHidden('status', $task->status),
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
                set::name($lang->task->keywords),
                input(set::name('keywords'), set::value($task->keywords))
            ),
            item
            (
                set::name($lang->task->mailto),
                mailto(set::items($mailtoOptions), set::value($task->mailto))
            )
        ),
        modalTrigger
        (
            modal
            (
                setID('modalTeam'),
                set::title($lang->task->teamMember),
                formBatch
                (
                    set::tagName('div'),
                    setID('teamTable'),
                    set::mode('add'),
                    !empty($task->team) ? set::data(array_values($task->team)) : null,
                    set::sortable(true),
                    set::size('sm'),
                    set::minRows(3),
                    set::onRenderRow(jsRaw('renderRowData')),
                    formBatchItem
                    (
                        set::name('id'),
                        set::width('32px'),
                        set::control('index')
                    ),
                    formBatchItem
                    (
                        set::name('team'),
                        set::label($lang->task->teamMember),
                        set::width('160px'),
                        set::control('picker'),
                        set::items($members)
                    ),
                    formBatchItem
                    (
                        set::name('estimateBox'),
                        set::label($lang->task->estimate),
                        set::width('135px'),
                        set::control('inputGroup'),
                        set::required(true),
                        inputControl
                        (
                            input(set::name("teamEstimate")),
                            to::suffix($lang->task->suffixHour),
                            set::suffixWidth(20)
                        )
                    ),
                    !empty($task->team) ? formBatchItem
                    (
                        set::name('consumedBox'),
                        set::label($lang->task->consumedAB),
                        set::width('135px'),
                        set::control('inputGroup'),
                        inputControl
                        (
                            input(set::name("teamConsumed")),
                            to::suffix($lang->task->suffixHour),
                            set::suffixWidth(20)
                        )
                    ) : null,
                    !empty($task->team) ? formBatchItem
                    (
                        set::name('leftBox'),
                        set::label($lang->task->left),
                        set::width('135px'),
                        set::control('inputGroup'),
                        set::required(true),
                        inputControl
                        (
                            input(set::name("teamLeft")),
                            to::suffix($lang->task->suffixHour),
                            set::suffixWidth(20)
                        )
                    ) : null,
                    formBatchItem
                    (
                        set::name('teamSource'),
                        set::control('input'),
                        set::hidden(true)
                    ),
                    set::actions(array(array('text' => $lang->save, 'class' => 'primary team-saveBtn'))),
                    on::click('.team-saveBtn')->call('saveTeam')
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
                        on::change('checkEstStartedAndDeadline'),
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
                        on::change('checkEstStartedAndDeadline'),
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
                            !empty($task->team) || !empty($task->children) ? set::readonly(true) : null
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
                    common::hasPriv('task', 'recordWorkhour') ? btn
                    (
                        setClass('ghost text-primary', !empty($task->children) ? 'disabled' : true),
                        icon('time'),
                        set::href(inlink('recordWorkhour', "id={$task->id}&from=edittask")),
                        setData('toggle', 'modal')
                    ) : null,
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
                            !empty($task->team) || !empty($task->children) ? set::readonly(true) : null
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
