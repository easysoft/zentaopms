<?php
declare(strict_types=1);
namespace zin;

global $lang, $config, $app;

$fields = defineFieldList('task.edit');

$task       = data('task');
$execution  = data('execution');
$project    = data('project');
$parentTask = data('parentTask');

$taskStatus = isset($task->rawStatus) ? $task->rawStatus : $task->status;

/* Compute status options. */
$statusOptions = (array)$lang->task->statusList;
if($taskStatus == 'wait') unset($statusOptions['pause']);

/* Compute disabled dates. */
$disabledEstStarted = $project && $project->taskDateLimit == 'limit' && !empty($parentTask) && helper::isZeroDate($parentTask->estStarted);
$disabledDeadline   = $project && $project->taskDateLimit == 'limit' && !empty($parentTask) && helper::isZeroDate($parentTask->deadline);

/* Compute readonly for estimate/left. */
$estimateReadonly = !empty($task->team) || !empty($task->children);
$leftReadonly     = !empty($task->team) || !empty($task->children);

/* Compute mode display. */
$modeOptions = null;
$modeText    = '';
if($taskStatus == 'wait' and $task->isParent == '0')
{
    $modeOptions = $lang->task->editModeList;
}
else
{
    $modeText = $task->mode == '' ? $lang->task->editModeList['single'] : zget($lang->task->editModeList, $task->mode);
}

$buildModule = function()
{
    global $lang;
    $task = data('task');

    return inputGroup
    (
        div(setClass('flex grow'),
            picker
            (
                setClass('w-full'),
                set::name('module'),
                set::value($task->module),
                set::items(data('modules')),
                set::width(2/3),
                set::required(true)
            )
        ),
        div(setClass('flex'),
            checkbox
            (
                setID('showAllModule'),
                set::rootClass('items-center ml-3'),
                set::name('showAllModule'),
                set::text($lang->all),
                set::value(1),
                set::checked(data('showAllModule'))
            )
        )
    );
};

$buildMode = function() use($modeOptions, $modeText)
{
    $task = data('task');

    if(empty($modeText))
    {
        return picker
        (
            set::name('mode'),
            set::value($task->mode),
            set::items($modeOptions),
            set::required(true)
        );
    }

    return div
    (
        setClass('flex items-center'),
        $modeText,
        input(setClass('hidden'), set::name('mode'), set::value($task->mode))
    );
};

$assignedToRequired = strpos(",{$config->task->edit->requiredFields},", ",assignedTo,") !== false;

$buildAssignedTo = function() use($assignedToRequired)
{
    global $lang;
    $task = data('task');
    $taskStatus = isset($task->rawStatus) ? $task->rawStatus : $task->status;

    $btnClass = 'input-group-btn team-group';
    if(empty($task->team) && !$task->mode) $btnClass .= ' hidden';

    if(!empty($task->team))
    {
        $isLinear = $task->mode == 'linear' && !in_array($taskStatus, array('done', 'closed'));
        return inputGroup
        (
            div(setClass('flex grow'),
                picker
                (
                    set::name('assignedTo'),
                    set::items(data('taskMembers')),
                    set::value($task->assignedTo),
                    set::required($assignedToRequired),
                    set::disabled($isLinear || $taskStatus == 'closed')
                )
            ),
            div(setClass('flex'),
                btn
                (
                    $lang->task->team,
                    setClass($btnClass),
                    set::url('#modalTeam'),
                    setData('toggle', 'modal')
                )
            )
        );
    }

    return inputGroup
    (
        div(setClass('flex grow'),
            taskAssignedTo
            (
                setClass('w-full'),
                set::name('assignedTo'),
                set::items(data('taskMembers')),
                set::value($task->assignedTo),
                set::required($assignedToRequired),
                set::disabled($taskStatus == 'closed'),
                data('manageLink') ? set::manageLink(data('manageLink')) : null
            )
        ),
        div(setClass('flex'),
            btn
            (
                $lang->task->team,
                setClass($btnClass),
                set::url('#modalTeam'),
                setData('toggle', 'modal')
            )
        )
    );
};

$buildConsumed = function()
{
    global $lang, $app;
    $task = data('task');
    $recordWorkhourDisabled = !empty($task->children) ? 'disabled' : '';

    return inputGroup
    (
        setClass('items-center'),
        span
        (
            setClass('span-text'),
            setID('consumedSpan'),
            $task->consumed . $lang->task->suffixHour
        ),
        $app->control->task->isClickable($task, 'recordWorkhour') ? btn
        (
            setClass('ghost text-primary', $recordWorkhourDisabled),
            icon('time'),
            set::href(inlink('recordWorkhour', "id={$task->id}&from=edittask")),
            setData('toggle', 'modal')
        ) : null,
        formHidden('consumed', helper::formatHours($task->consumed))
    );
};

/* ====== Main column fields (position = info) ====== */

$fields->field('name')
    ->position('info')
    ->control('colorInput', array('colorValue' => data('task.color')))
    ->width('full')
    ->required(true)
    ->placeholder($lang->task->name)
    ->value(data('task.name'));

$fields->field('desc')
    ->position('info')
    ->width('full')
    ->control('editor')
    ->value($task->desc);

$fields->field('story')
    ->hidden($execution->lifetime == 'ops')
    ->position('info')
    ->width('1/2')
    ->control('picker')
    ->items(data('stories'))
    ->value($task->story);

$buildFiles = function()
{
    $task  = data('task');
    $files = array();
    foreach($task->files as $file)
    {
        if($file->extra != '') continue;
        $files[] = $file;
    }

    return fileSelector($files ? set::defaultFiles(array_values($files)) : null);
};

$fields->field('files')
    ->position('info')
    ->width('full')
    ->control($buildFiles);

$fields->field('lastEditedDate')
    ->position('info')
    ->control('hidden')
    ->value(helper::isZeroDate($task->lastEditedDate) ? '' : $task->lastEditedDate);

/* ====== Sidebar: legendBasic ====== */

$fields->field('execution')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->control('picker')
    ->items(data('executions'))
    ->value($task->execution);

$fields->field('module')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->required(strpos(",{$config->task->edit->requiredFields},", ",module,") !== false)
    ->control($buildModule);

$fields->field('parent')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->control('picker')
    ->items(data('tasks'))
    ->value($task->parent)
    ->disabled(!empty($task->mode))
    ->hidden(!($task->parent >= 0 && $config->vision != 'lite'));

$fields->field('mode')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->control($buildMode);

$fields->field('assignedTo')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->required($assignedToRequired)
    ->control($buildAssignedTo);

$fields->field('type')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->required(true)
    ->control('picker')
    ->items($lang->task->typeList)
    ->value($task->type);

$fields->field('status')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->required(true)
    ->control('picker')
    ->items($statusOptions)
    ->value($taskStatus)
    ->hidden(!empty($task->children));

$fields->field('pri')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->required(strpos(",{$config->task->edit->requiredFields},", ",pri,") !== false)
    ->control('priPicker')
    ->items($lang->task->priList)
    ->value($task->pri);

$fields->field('progress')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->control('progressCircle', array('percent' => $task->progress, 'circleColor' => 'var(--color-success-500)', 'circleBg' => 'var(--color-border)', 'circleWidth' => 1));

$fields->field('keywords')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->control('input')
    ->value($task->keywords);

$fields->field('mailto')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->control('mailto')
    ->items(data('users'))
    ->value($task->mailto);

/* ====== Sidebar: legendEffort ====== */

$fields->field('estStarted')
    ->position('basic')
    ->group($lang->task->legendEffort)
    ->required(strpos(",{$config->task->edit->requiredFields},", ",estStarted,") !== false)
    ->control('datePicker')
    ->disabled($disabledEstStarted)
    ->value((helper::isZeroDate($task->estStarted) || $disabledEstStarted) ? null : $task->estStarted);

$fields->field('deadline')
    ->position('basic')
    ->group($lang->task->legendEffort)
    ->required(strpos(",{$config->task->edit->requiredFields},", ",deadline,") !== false)
    ->control('datePicker')
    ->disabled($disabledDeadline)
    ->value((helper::isZeroDate($task->deadline) || $disabledDeadline) ? null : $task->deadline);

$fields->field('estimate')
    ->position('basic')
    ->group($lang->task->legendEffort)
    ->required(strpos(",{$config->task->edit->requiredFields},", ",estimate,") !== false)
    ->control('inputControl', array('suffix' => $lang->task->suffixHour, 'suffixWidth' => 20))
    ->readonly($estimateReadonly)
    ->value(helper::formatHours($task->estimate));

$fields->field('consumed')
    ->position('basic')
    ->group($lang->task->legendEffort)
    ->control($buildConsumed);

$fields->field('left')
    ->position('basic')
    ->group($lang->task->legendEffort)
    ->control('inputControl', array('suffix' => $lang->task->suffixHour, 'suffixWidth' => 20))
    ->readonly($leftReadonly)
    ->value(helper::formatHours($task->left));

/* ====== Sidebar: legendLife ====== */

$fields->field('realStarted')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('datetimePicker')
    ->value(helper::isZeroDate($task->realStarted) ? '' : $task->realStarted);

$fields->field('finishedBy')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('picker')
    ->items(data('members'))
    ->value($task->finishedBy);

$fields->field('finishedDate')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('datetimePicker')
    ->value(helper::isZeroDate($task->finishedDate) ? '' : $task->finishedDate);

$fields->field('canceledBy')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('picker')
    ->items(data('users'))
    ->value($task->canceledBy);

$fields->field('canceledDate')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('datetimePicker')
    ->value(helper::isZeroDate($task->canceledDate) ? '' : $task->canceledDate);

$fields->field('closedBy')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('picker')
    ->items(data('users'))
    ->value($task->closedBy);

$fields->field('closedReason')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('picker')
    ->items($lang->task->reasonList)
    ->value($task->closedReason);

$fields->field('closedDate')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('datetimePicker')
    ->value(helper::isZeroDate($task->closedDate) ? '' : $task->closedDate);
