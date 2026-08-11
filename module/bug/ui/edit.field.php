<?php
namespace zin;

global $lang, $app, $config;

$files = array();
if(!empty(data('bug.files')))
{
    foreach(data('bug.files') as $fileInfo)
    {
        if($fileInfo->extra != '') continue;
        $files[] = $fileInfo;
    }
}

$fields = defineFieldList('bug.edit');

$fields->field('id')->position('info')->control('hidden')->value(data('bug.id'));

$fields->field('title')->position('info')->control('colorInput', array('colorValue' => data('bug.color')));

$fields->field('steps')
    ->position('info')
    ->label($lang->bug->legendSteps)
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',steps,') !== false)
    ->control('editor')
    ->value(data('bug.steps'));

$fields->field('files')->position('info')->control(array('control' => 'fileSelector', 'defaultFiles' => $files ?: null))->value('');

$fields->field('product')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->hidden(!empty(data('product.shadow')))
    ->control(array
    (
        'control' => 'inputGroup',
        'items' => array
        (
            array('control' => 'picker', 'name' => 'product', 'items' => data('products'), 'required' => true, 'value' => data('product.id')),
            data('product.type') != 'normal' ? array('control' => 'picker', 'name' => 'branch', 'items' => data('branchTagOption'), 'required' => true, 'value' => data('bug.branch')) : null
        )
    ));

$fields->field('module')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',module,') !== false)
    ->control(array('control' => 'modulePicker', 'required' => true, 'manageLink' => createLink('tree', 'browse', 'rootID=' . data('bug.productID') . '&view=bug&currentModuleID=0&branch=' . data('bug.branch'))))
    ->items(data('moduleOptionMenu'))
    ->value(data('bug.module'));

$fields->field('plan')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->id('planBox')
    ->hidden(!empty(data('product.shadow')) && !empty(data('project')) && empty(data('project.multiple')))
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',plan,') !== false)
    ->control('picker')
    ->items(data('plans'))
    ->value(data('bug.plan'));

$fields->field('case')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->id('caseBox')
    ->label($lang->bug->fromCase)
    ->control(array('control' => 'picker', 'maxItemsCount' => $config->maxCount))
    ->items(data('cases'))
    ->value(data('bug.case'));

$fields->field('type')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',type,') !== false)
    ->control('picker')
    ->items($lang->bug->typeList)
    ->value(data('bug.type'));

$fields->field('severity')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',severity,') !== false)
    ->control(array('control' => 'severityPicker', 'required' => true))
    ->items($lang->bug->severityList)
    ->value(data('bug.severity'));

$fields->field('pri')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',pri,') !== false)
    ->control(array('control' => 'priPicker', 'required' => true))
    ->items($lang->bug->priList)
    ->value(data('bug.pri'));

$fields->field('status')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->disabled(true)
    ->control('picker')
    ->items($lang->bug->statusList)
    ->value(data('bug.status'));

$fields->field('confirmed')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->disabled(true)
    ->control('picker')
    ->items($lang->bug->confirmedList)
    ->value(data('bug.confirmed'));

$fields->field('assignedTo')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',assignedTo,') !== false)
    ->control(function()
    {
        global $lang;
        return inputGroup
        (
            picker
            (
                set::disabled(data('bug.status') == 'closed'),
                set::name('assignedTo'),
                set::items(data('assignedToList')),
                set::value(data('bug.assignedTo'))
            ),
            span
            (
                set('class', 'input-group-addon'),
                a
                (
                    set('id', 'allUsers'),
                    set('href', 'javascript:;'),
                    $lang->bug->loadAll
                )
            )
        );
    });

$fields->field('deadline')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',deadline,') !== false)
    ->control('datePicker')
    ->value(data('bug.deadline'));

$fields->field('feedbackBy')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',feedbackBy,') !== false)
    ->control('input')
    ->value(data('bug.feedbackBy'));

$fields->field('notifyEmail')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->control('input')
    ->value(data('bug.notifyEmail'));

$fields->field('os')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',os,') !== false)
    ->control(array('control' => 'picker', 'menu' => array('checkbox' => true), 'multiple' => true))
    ->items($lang->bug->osList)
    ->value(data('bug.os'));

$fields->field('browser')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',browser,') !== false)
    ->control(array('control' => 'picker', 'menu' => array('checkbox' => true), 'multiple' => true))
    ->items($lang->bug->browserList)
    ->value(data('bug.browser'));

$fields->field('keywords')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',keywords,') !== false)
    ->control('input')
    ->value(data('bug.keywords'));

$fields->field('mailto')
    ->position('basic')
    ->group($lang->bug->legendBasicInfo)
    ->control(array('control' => 'mailto', 'items' => data('users'), 'value' => data('bug.mailto')));

$fields->field('project')
    ->position('basic')
    ->group(!empty(data('project.multiple')) ? $lang->bug->legendPRJExecStoryTask : $lang->bug->legendExecStoryTask)
    ->id('projectBox')
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',project,') !== false)
    ->control(array('control' => 'picker', 'required' => !empty(data('product.shadow'))))
    ->items(data('projects'))
    ->value(data('bug.project'));

$fields->field('execution')
    ->position('basic')
    ->group(!empty(data('project.multiple')) ? $lang->bug->legendPRJExecStoryTask : $lang->bug->legendExecStoryTask)
    ->id('executionBox')
    ->hidden(!empty(data('execution')) && empty(data('execution.multiple')))
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',execution,') !== false)
    ->control('picker')
    ->items(data('executions'))
    ->value(data('bug.execution'));

$fields->field('story')
    ->position('basic')
    ->group(!empty(data('project.multiple')) ? $lang->bug->legendPRJExecStoryTask : $lang->bug->legendExecStoryTask)
    ->id('storyBox')
    ->control('picker')
    ->items(data('stories'))
    ->value(data('bug.story'));

$fields->field('task')
    ->position('basic')
    ->group(!empty(data('project.multiple')) ? $lang->bug->legendPRJExecStoryTask : $lang->bug->legendExecStoryTask)
    ->id('taskBox')
    ->control('picker')
    ->items(data('tasks'))
    ->value(data('bug.task'));

$fields->field('openedBy')
    ->position('basic')
    ->group($lang->bug->legendLife)
    ->disabled(true)
    ->control('picker')
    ->items(data('users'))
    ->value(data('bug.openedBy'));

$fields->field('openedBuild')
    ->position('basic')
    ->group($lang->bug->legendLife)
    ->required(strpos(",{$config->bug->edit->requiredFields},", ',openedBuild,') !== false)
    ->control(function()
    {
        global $lang;
        return inputGroup
        (
            set('id', 'openedBuildBox'),
            picker
            (
                set::name('openedBuild[]'),
                set::items(data('openedBuilds')),
                set::value(data('bug.openedBuild')),
                set::menu(array('checkbox' => true)),
                set::multiple(true)
            ),
            span
            (
                set('class', 'input-group-addon'),
                a
                (
                    set('id', 'allBuilds'),
                    set('href', 'javascript:;'),
                    $lang->bug->loadAll
                )
            )
        );
    });

$fields->field('resolvedBy')
    ->position('basic')
    ->group($lang->bug->legendLife)
    ->control('picker')
    ->items(data('users'))
    ->value(data('bug.resolvedBy'));

$fields->field('resolvedDate')
    ->position('basic')
    ->group($lang->bug->legendLife)
    ->control('datetimePicker')
    ->value(data('bug.resolvedDate'));

$fields->field('resolvedBuild')
    ->position('basic')
    ->group($lang->bug->legendLife)
    ->control(function()
    {
        global $lang;
        return inputGroup
        (
            set('id', 'resolvedBuildBox'),
            picker
            (
                set::name('resolvedBuild'),
                set::items(data('resolvedBuilds')),
                set::value(data('bug.resolvedBuild'))
            ),
            span
            (
                set('class', 'input-group-addon'),
                a
                (
                    set('id', 'allBuilds'),
                    set('href', 'javascript:;'),
                    $lang->bug->loadAll
                )
            )
        );
    });

$fields->field('resolution')
    ->position('basic')
    ->group($lang->bug->legendLife)
    ->control('picker')
    ->items($lang->bug->resolutionList)
    ->value(data('bug.resolution'));

$fields->field('duplicateBug')
    ->position('basic')
    ->group($lang->bug->legendLife)
    ->hidden(data('bug.resolution') != 'duplicate')
    ->control(array('control' => 'picker', 'items' => createLink('bug', 'ajaxGetProductBugs', 'productID=&bugID=' . data('bug.id') . '&search={search}' . (!empty(data('bug.duplicateBug')) ? '&duplicateBug=' . data('bug.duplicateBug') : ''))))
    ->placeholder($lang->bug->placeholder->duplicate)
    ->value(data('bug.duplicateBug'));

$fields->field('closedBy')
    ->position('basic')
    ->group($lang->bug->legendLife)
    ->control('picker')
    ->items(data('users'))
    ->value(data('bug.closedBy'));

$fields->field('closedDate')
    ->position('basic')
    ->group($lang->bug->legendLife)
    ->control('datetimePicker')
    ->value(data('bug.closedDate'));

$fields->field('relatedBug')
    ->position('basic')
    ->group($lang->bug->legendMisc)
    ->control(function()
    {
        global $lang;
        return inputGroup
        (
            set('id', 'linkBugsBox'),
            picker
            (
                set::multiple(true),
                set::name('relatedBug'),
                set::items(data('bug.relatedBugTitles') ?: array()),
                set::value(data('bug.relatedBug'))
            ),
            common::hasPriv('bug', 'linkBugs') && !isInModal() ? inputGroupAddon
            (
                setClass('p-0'),
                btn
                (
                    setID('linkBug'),
                    setClass('ghost text-primary'),
                    $lang->bug->linkBugs
                )
            ) : null
        );
    });


$fields->field('testtask')
    ->position('basic')
    ->group($lang->bug->legendMisc)
    ->id('testtaskBox')
    ->control('picker')
    ->items(data('testtasks'))
    ->value(data('bug.testtask'));
