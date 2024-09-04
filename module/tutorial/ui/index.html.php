<?php
declare(strict_types=1);
/**
 * The trash view file of action module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang XuePeng <wangxuepeng@easycorp.ltd>
 * @package     tutorial
 * @link        https://www.zentao.net
 */
namespace zin;

/* 使用 pagebase 渲染页面并启用 ZUI。 Render page with "pagebase" and enabled zui framework. */
set::zui(true);

/* 将所有教程按类型进行分组。Grouped all guides by type. */
$groupedGuides = array();
$currentType   = 'starter'; // 当前选中的类型。Current active type.
foreach($guides as $guideName => $guide)
{
    $taskIndex = 0;
    foreach($guide->tasks as $tasID => $task)
    {
        $task['index'] = $taskIndex++;
        foreach($task['steps'] as $index => $step)
        {
            $step['index']     = $index;
            $step['guideName'] = $guideName;
            $step['taskName']  = $task['name'];
            $task['steps'][$index] = $step;
        }
        $guide->tasks[$tasID] = $task;
    }
    $groupedGuides[$guide->type][$guideName] = $guide;

    if($currentTask === $guideName || str_starts_with($currentTask, "$guideName.")) $currentType = $guide->type;
}

$buildStepItem = function(array $step, int $index)
{
    return li
    (
        setClass('tutorial-step pl-1'),
        setData('step', $index),
        div
        (
            setClass('tutorial-step-title flex items-center h-6 border-l px-2 text-gray'),
            $step['title']
        ),
    );
};

/**
 * 生成任务列表（simpleList）条目。
 * Build configuration of simpleList item for tasks.
 *
 * @param array  $task     任务配置。Task configuration.
 * @param string $taskName 任务键。Task key.
 * @return node
 */
$buildTaskItem = function(array $task, string $taskName) use ($lang, $buildStepItem)
{
    return li
    (
        setClass('tutorial-task mt-2'),
        setData('name', $taskName),
        setData('status', 'wait'),
        div
        (
            setClass('tutorial-task-item row items-center gap-2 canvas h-8 cursor-pointer'),
            div($task['title'], setClass('tutorial-task-title flex-auto')),
            div
            (
                setClass('tutorial-task-actions flex-none row items-center'),
                btn
                (
                    span($lang->tutorial->start, setData('type', 'start')),
                    span($lang->tutorial->restart, setData('type', 'restart')),
                    span($lang->tutorial->continue, setData('type', 'continue')),
                    setClass('size-sm tutorial-task-start')
                )
            )
        ),
        div
        (
            setClass('tutorial-task-steps pt-1'),
            simpleList
            (
                setClass('tutorial-step-list'),
                set::items($task['steps']),
                set::onRenderItem($buildStepItem)
            )
        )
    );
};

/**
 * 生成教程指南列表（simpleList）条目。
 * Build configuration of simpleList item for tutorial guides.
 *
 * @param object $guide      指南对象。Guide object.
 * @param string $guideName  指南键。Guide guideName.
 * @return node
 */
$buildGuideItem = function(object $guide, string $guideName) use ($buildTaskItem)
{
    return li
    (
        setClass('tutorial-guide mb-2.5'),
        setData('name', $guideName),
        div
        (
            setClass('tutorial-guide-item row items-center gap-2 canvas h-10 cursor-pointer border rounded-sm px-2.5 hover:shadow group'),
            icon($guide->icon, setClass('tutorial-guide-icon flex-none')),
            div($guide->title, setClass('tutorial-guide-title flex-auto text-md')),
            icon('import rotate-270 primary-pale rounded-full w-5 h-5 center opacity-0 group-hover:opacity-100 tutorial-guide-trailing-icon')
        ),
        div
        (
            setClass('tutorial-guide-tasks'),
            simpleList
            (
                setClass('tutorial-task-list'),
                set::items($guide->tasks),
                set::onRenderItem($buildTaskItem)
            )
        )
    );
};

/**
 * 生成教程选项卡面板（tabPane）配置。
 * Build configuration of tabPane for tutorial.
 *
 * @param string $type 任务类型。Task type.
 * @return tabPane
 */
$buildTutorialTabPane = function($type) use ($groupedGuides, $lang, $currentType, $buildGuideItem)
{
    $guides = $groupedGuides[$type];
    return tabPane
    (
        set::title($lang->tutorial->guideTypes[$type]),
        set::active($type === $currentType),
        set::class('px-2.5'),
        set::key($type),
        simpleList
        (
            setData('type', $type),
            setClass('tutorial-guide-list', ($type == 'starter' && is_array($guides) && count($guides) === 1) ? 'is-single-guide' : ''),
            set::items($guides),
            set::onRenderItem($buildGuideItem)
        )
    );
};

jsVar('guides', $guides);
jsVar('lang', array
(
    'nextTask'         => $lang->tutorial->nextTask,
    'nextStep'         => $lang->tutorial->nextStep,
    'clickTipFormat'   => $lang->tutorial->clickTipFormat,
    'clickAndOpenIt'   => $lang->tutorial->clickAndOpenIt,
    'congratulateTask' => $lang->tutorial->congratulateTask
));

div
(
    setID('pageContainer'),
    setCssVar('--sidebar-width', '240px'), // 侧边栏宽度。Sidebar width.
    setClass('canvas absolute left-0 top-0 right-0 bottom-0 overflow-hidden'),
    div
    (
        setID('iframeWrapper'), // 应用页面容器。App iframe container.
        setClass('absolute left-0 top-0 bottom-0'),
        style::right('--sidebar-width'),
        h::iframe
        (
            setID('iframePage'),
            setClass('w-full h-full border-none'),
            set::name('iframePage'),
            set::src(createLink('index', 'index'))
        )
    ),
    div
    (
        setID('sidebar'), // 侧边栏。Sidebar.
        setClass('absolute top-0 bottom-0 right-0 border-l'),
        style::width('--sidebar-width'),
        ($currentGuide && $currentTask) ? on::init()->call('activeTask', $currentGuide, $currentTask) : null,
        h::header
        (
            setClass('row items-center flex-nowrap p-2'),
            h2
            (
                $lang->tutorial->common,
                setClass('flex-auto text-md')
            ),
            div
            (
                setClass('flex-none'),
                btn
                (
                    setClass('size-sm'),
                    set::type('ghost'),
                    set::url(inlink('quit')),
                    set::hint($lang->tutorial->exit),
                    set::icon('close')
                )
            )
        ),
        tabs
        (
            setID('tutorialTabs'),
            set::headerClass('px-2.5'),
            array_map($buildTutorialTabPane, array_keys($lang->tutorial->guideTypes)),
            on::click('.tutorial-task-start')->do('handleClickTask(event)'),
            on::click('.tutorial-guide-item')->do('handleClickGuide(event)')
        )
    )
);

render('pagebase');
