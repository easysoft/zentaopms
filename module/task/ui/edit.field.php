<?php
declare(strict_types=1);
namespace zin;

global $lang, $config, $app;

$fields = defineFieldList('task.edit');

$taskStatus = data('task.rawStatus') !== null ? data('task.rawStatus') : data('task.status');

/* Compute status options. */
$statusOptions = (array)$lang->task->statusList;
if($taskStatus == 'wait') unset($statusOptions['pause']);

/* Compute disabled dates. */
$disabledEstStarted = data('project') && data('project.taskDateLimit') == 'limit' && !empty(data('parentTask')) && helper::isZeroDate(data('parentTask.estStarted'));
$disabledDeadline   = data('project') && data('project.taskDateLimit') == 'limit' && !empty(data('parentTask')) && helper::isZeroDate(data('parentTask.deadline'));

/* Compute readonly for estimate/left. */
$estimateReadonly = !empty(data('task.team')) || !empty(data('task.children'));
$leftReadonly     = !empty(data('task.team')) || !empty(data('task.children'));

/* Compute mode display. */
$modeOptions = null;
$modeText    = '';
if($taskStatus == 'wait' and data('task.isParent') == '0')
{
    $modeOptions = $lang->task->editModeList;
}
else
{
    $modeText = data('task.mode') == '' ? $lang->task->editModeList['single'] : zget($lang->task->editModeList, data('task.mode'));
}

$buildModule = function()
{
    global $lang;

    return inputGroup
    (
        div(setClass('flex grow'),
            picker
            (
                setClass('w-full'),
                set::name('module'),
                set::value(data('task.module')),
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
    if(empty($modeText))
    {
        return picker
        (
            set::name('mode'),
            set::value(data('task.mode')),
            set::items($modeOptions),
            set::required(true)
        );
    }

    return div
    (
        setClass('flex items-center'),
        $modeText,
        input(setClass('hidden'), set::name('mode'), set::value(data('task.mode')))
    );
};

$assignedToRequired = strpos(",{$config->task->edit->requiredFields},", ",assignedTo,") !== false;

$buildAssignedTo = function() use($assignedToRequired)
{
    global $lang;
    $taskStatus = data('task.rawStatus') !== null ? data('task.rawStatus') : data('task.status');

    $btnClass = 'input-group-btn team-group';
    if(empty(data('task.team')) && !data('task.mode')) $btnClass .= ' hidden';

    if(!empty(data('task.team')))
    {
        $isLinear = data('task.mode') == 'linear' && !in_array($taskStatus, array('done', 'closed'));
        return inputGroup
        (
            div(setClass('flex grow'),
                picker
                (
                    set::name('assignedTo'),
                    set::items(data('taskMembers')),
                    set::value(data('task.assignedTo')),
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
                set::value(data('task.assignedTo')),
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
    $recordWorkhourDisabled = !empty(data('task.children')) ? 'disabled' : '';

    return inputGroup
    (
        setClass('items-center'),
        span
        (
            setClass('span-text'),
            setID('consumedSpan'),
            data('task.consumed') . $lang->task->suffixHour
        ),
        $app->control->task->isClickable(data('task'), 'recordWorkhour') ? btn
        (
            setClass('ghost text-primary', $recordWorkhourDisabled),
            icon('time'),
            set::href(inlink('recordWorkhour', 'id=' . data('task.id') . '&from=edittask')),
            setData('toggle', 'modal')
        ) : null,
        formHidden('consumed', helper::formatHours(data('task.consumed')))
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
    ->value(data('task.desc'));

$fields->field('story')
    ->hidden(data('execution.lifetime') == 'ops')
    ->position('info')
    ->width('1/2')
    ->control('picker')
    ->items(data('stories'))
    ->value(data('task.story'));

$buildFiles = function()
{
    $files = array();
    if(!empty(data('task.files')))
    {
        foreach(data('task.files') as $fileInfo)
        {
            if($fileInfo->extra != '') continue;
            $files[] = $fileInfo;
        }
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
    ->value(helper::isZeroDate(data('task.lastEditedDate')) ? '' : data('task.lastEditedDate'));

/* ====== Sidebar: legendBasic ====== */

$fields->field('execution')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->control('picker')
    ->items(data('executions'))
    ->value(data('task.execution'));

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
    ->value(data('task.parent'))
    ->disabled(!empty(data('task.mode')))
    ->hidden(!(data('task.parent') >= 0 && $config->vision != 'lite'));

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
    ->value(data('task.type'));

$fields->field('status')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->required(true)
    ->control('picker')
    ->items($statusOptions)
    ->value($taskStatus)
    ->hidden(!empty(data('task.children')));

$fields->field('pri')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->required(strpos(",{$config->task->edit->requiredFields},", ",pri,") !== false)
    ->control('priPicker')
    ->items($lang->task->priList)
    ->value(data('task.pri'));

$fields->field('progress')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->control('progressCircle', array('percent' => data('task.progress'), 'circleColor' => 'var(--color-success-500)', 'circleBg' => 'var(--color-border)', 'circleWidth' => 1));

$fields->field('keywords')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->control('input')
    ->value(data('task.keywords'));

$fields->field('mailto')
    ->position('basic')
    ->group($lang->task->legendBasic)
    ->control('mailto')
    ->items(data('users'))
    ->value(data('task.mailto'));

/* ====== Sidebar: legendEffort ====== */

$fields->field('estStarted')
    ->position('basic')
    ->group($lang->task->legendEffort)
    ->required(strpos(",{$config->task->edit->requiredFields},", ",estStarted,") !== false)
    ->control('datePicker')
    ->disabled($disabledEstStarted)
    ->value((helper::isZeroDate(data('task.estStarted')) || $disabledEstStarted) ? null : data('task.estStarted'));

$fields->field('deadline')
    ->position('basic')
    ->group($lang->task->legendEffort)
    ->required(strpos(",{$config->task->edit->requiredFields},", ",deadline,") !== false)
    ->control('datePicker')
    ->disabled($disabledDeadline)
    ->value((helper::isZeroDate(data('task.deadline')) || $disabledDeadline) ? null : data('task.deadline'));

$fields->field('estimate')
    ->position('basic')
    ->group($lang->task->legendEffort)
    ->required(strpos(",{$config->task->edit->requiredFields},", ",estimate,") !== false)
    ->control('inputControl', array('suffix' => $lang->task->suffixHour, 'suffixWidth' => 20))
    ->readonly($estimateReadonly)
    ->value(helper::formatHours(data('task.estimate')));

$fields->field('consumed')
    ->position('basic')
    ->group($lang->task->legendEffort)
    ->control($buildConsumed);

$fields->field('left')
    ->position('basic')
    ->group($lang->task->legendEffort)
    ->control('inputControl', array('suffix' => $lang->task->suffixHour, 'suffixWidth' => 20))
    ->readonly($leftReadonly)
    ->value(helper::formatHours(data('task.left')));

/* ====== Sidebar: legendLife ====== */

$fields->field('realStarted')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('datetimePicker')
    ->value(helper::isZeroDate(data('task.realStarted')) ? '' : data('task.realStarted'));

$fields->field('finishedBy')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('picker')
    ->items(data('members'))
    ->value(data('task.finishedBy'));

$fields->field('finishedDate')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('datetimePicker')
    ->value(helper::isZeroDate(data('task.finishedDate')) ? '' : data('task.finishedDate'));

$fields->field('canceledBy')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('picker')
    ->items(data('users'))
    ->value(data('task.canceledBy'));

$fields->field('canceledDate')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('datetimePicker')
    ->value(helper::isZeroDate(data('task.canceledDate')) ? '' : data('task.canceledDate'));

$fields->field('closedBy')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('picker')
    ->items(data('users'))
    ->value(data('task.closedBy'));

$fields->field('closedReason')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('picker')
    ->items($lang->task->reasonList)
    ->value(data('task.closedReason'));

$fields->field('closedDate')
    ->position('basic')
    ->group($lang->task->legendLife)
    ->control('datetimePicker')
    ->value(helper::isZeroDate(data('task.closedDate')) ? '' : data('task.closedDate'));
