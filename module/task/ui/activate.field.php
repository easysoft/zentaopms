<?php
namespace zin;
global $lang, $config;

$fields = defineFieldList('task.activate');

/* Mode field. */
$fields->field('mode')
    ->label($lang->task->mode)
    ->width('1/4')
    ->hidden(!data('isMultiple'))
    ->control(function()
    {
        global $lang;
        $task = data('task');
        return inputGroup
        (
            setClass('no-background'),
            zget($lang->task->modeList, $task->mode),
            input
            (
                setClass('hidden'),
                set::name('mode'),
                set::value($task->mode)
            )
        );
    });

/* Build assignedTo control combined with team button and manage team checkbox. */
$buildAssignedTo = function()
{
    global $lang, $config;
    $task        = data('task');
    $members     = data('members');
    $isMultiple  = data('isMultiple');
    $teamMembers = data('teamMembers') ?: array();

    $modalTeamBtn  = array();
    $manageTeamBox = array();
    if($isMultiple)
    {
        $modalTeamBtn = btn(
            set::text($lang->task->team),
            setClass('team-group hidden'),
            set::url('#modalTeam'),
            $task->mode == 'multi' ? on::click('disableMembers') : null,
            setData(array('toggle' => 'modal'))
        );
        $manageTeamBox = formGroup(
            set::width('1/10'),
            setClass('items-center'),
            checkbox
            (
                setID('multiple'),
                set::name('multiple'),
                set::text($lang->task->manageTeam),
                set::rootClass('ml-4'),
                on::click('manageTeam')
            )
        );
    }

    return div
    (
        setClass('flex items-center flex-1'),
        inputGroup
        (
            picker
            (
                setID('assignedTo'),
                set::name('assignedTo'),
                set::items($isMultiple ? $teamMembers : $members),
                set::value($isMultiple ? '' : $task->finishedBy),
                set::required(strpos(",{$config->task->activate->requiredFields},", ',assignedTo,') !== false),
                on::change('setTeamUser')
            ),
            $modalTeamBtn
        ),
        $manageTeamBox
    );
};

$fields->field('assignedTo')
    ->label($lang->task->assignedTo)
    ->width('1/2')
    ->required(data('isMultiple') || strpos(",{$config->task->activate->requiredFields},", ',assignedTo,') !== false)
    ->control($buildAssignedTo);

/* Left field. */
$fields->field('left')
    ->label($lang->task->left)
    ->width('1/2')
    ->hidden(data('task.isParent') != '0')
    ->control(function()
    {
        global $lang;
        return inputControl
        (
            input(set::name('left')),
            to::suffix($lang->task->suffixHour),
            set::suffixWidth(20)
        );
    });

/* Comment field. */
$fields->field('comment')
    ->label($lang->comment)
    ->width('full')
    ->rows('5')
    ->control('editor');
