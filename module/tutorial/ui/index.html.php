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

set::zui(true);

$i = 0;
$taskGroup = array_map(function($key, $task) use(&$i)
{
    $i++;
    return li
        (
            setClass('w-full'),
            setData(array('name' => $key)),
            a
            (
                setData(array('name' => $key)),
                set::href('javascript:;'),
                setClass('btn-task pull-left'),
                span($i .'. '),
                span(
                    setClass('task-name'),
                    $task['title']
                )
            ),
        );
}, array_keys($tasks), $tasks);

$i=0;
$tutorialTasks =  array();
foreach($tasks as $key => &$task)
{
    $i++;
    $task['id'] = $i;
    $task['name'] = $task['title'];
    $task['url'] = helper::createLink($task['nav']['module'], $task['nav']['method'], isset($task['nav']['vars']) ? $task['nav']['vars'] : '');
    $tutorialTasks[$key] = $task;
}

jsVar('tutorialReferer', $referer);
jsVar('ajaxSetTasksUrl', inlink('ajaxSetTasks'));
jsVar('tutorialTasks', $tutorialTasks);
jsVar('defaultTask', $current);
jsVar('settingString', $setting);
jsVar('langTargetPageTip', $lang->tutorial->targetPageTip);
jsVar('langTarget', $lang->tutorial->target);
jsVar('langTargetAppTip', $lang->tutorial->targetAppTip);
jsVar('langRequiredTip', $lang->tutorial->requiredTip);

div
(
    setID('pageContainer'),
    div
    (
        setID('iframeWrapper'),
        h::iframe
        (
            setID('iframePage'),
            set::name('iframePage'),
            set::src(createLink('index', 'index')),
        )
    ),
    div(setID('taskModalBack')),
    div
    (
        setID('taskModal'),
        div
        (
            setClass('close'),
            set::icon('close'),
        ),
        div
        (
            setClass('finish-all'),
            div
            (
                setClass('start-icon'),
                icon(
                    setClass('icon-front'),
                    'check-circle'
                ),
            ),
            h3($lang->tutorial->congratulation),
            btn
            (
                set::icon('restart'),
                setClass('btn btn-outline btn-reset-tasks'),
                $lang->tutorial->restart
            ),
            ' ',
            btn
            (
                setClass('btn btn-outline'),
                set::url(createLink('tutorial', 'quit')),
                $lang->tutorial->exit
            )
        ),
        div
        (
            setClass('finish'),
            div
            (
                setClass('start-icon'),
                icon(
                    setClass('icon-front'),
                    'check-circle'
                ),
            ),
            h3(
                $lang->tutorial->congratulateTask,
                "【",
                span
                (
                    setClass('task-name-current'),
                ),
                "】！"
            ),
            btn
            (
                setClass('btn btn-outline btn-next-task btn-task'),
                $lang->tutorial->nextTask,
                set::trailingIcon('angle-right')
            )
        )
    ),
    div
    (
        setID('sidebar'),
        h::header
        (
            setClass('bg-primary'),
            div
            (
                setClass('start-icon'),
                icon(
                    setClass('icon-back'),
                    'certificate'
                ),
                icon(
                    setClass('icon-front text-secondary'),
                    'flag'
                )
            ),
            h2($lang->tutorial->common),
            div
            (
                setClass('actions'),
                btn
                (
                    setClass('size-sm'),
                    set::type('danger'),
                    set::url(inlink('quit')),
                    $lang->tutorial->exit,
                )
            )
        ),
        h::section
        (
            setID('current'),
            h4('当前任务'),
            div
            (
                setID('task'),
                setClass('panel'),
                div
                (
                    setClass('panel-heading bg-secondary'),
                    h::strong
                    (
                        span
                        (
                            setClass('task-id-current'),
                        ),
                        '. ',
                        span
                        (
                            setClass('task-name task-name-current'),
                        )
                    )
                ),
                div
                (
                    setClass('panel-body'),
                    div
                    (
                        setClass('task-desc'),
                        p(),
                        ul
                        (
                            li
                            (
                                setData(array('target' => 'nav')),
                                setClass('wait'),
                            ),
                            li
                            (
                                setData(array('target' => 'form')),
                                setClass('wait'),
                            ),
                            li
                            (
                                setData(array('target' => 'submit')),
                                setClass('wait'),
                            )
                        )
                    ),
                    a(
                        setID('openTaskPage'),
                        setClass('btn-open-target-page hl-primary open'),
                        set::href('javascript:;'),
                        div
                        (
                            setClass('normal'),
                            icon('magic'),
                            html($lang->tutorial->openTargetPage)
                        ),
                        div
                        (
                            setClass('opened'),
                            icon('flag'),
                            html($lang->tutorial->atTargetPage)
                        ),
                        div
                        (
                            setClass('reload'),
                            icon('restart'),
                            html($lang->tutorial->reloadTargetPage)
                        )
                    ),
                    div
                    (
                        setClass('alert warning-pale flex items-center p-4 my-0'),
                        $lang->tutorial->dataNotSave
                    )
                )
            ),
            div
            (
                setClass('clearfix actions'),
                btn
                (
                    set::icon('arrow-left'),
                    set::size('sm'),
                    setClass('btn-prev-task btn-task circle'),
                    $lang->tutorial->previous,
                ),
                btn
                (
                    set::trailingIcon('arrow-right'),
                    set::size('sm'),
                    set::type('primary'),
                    setClass('btn-next-task btn-task circle pull-right'),
                    $lang->tutorial->nextTask,
                )
            )
        ),
        h::section
        (
            setID('all'),
            h4(
                $lang->tutorial->allTasks,
                '(',
                span
                (
                    setClass('task-num-finish'),
                    0,
                ),
                '/',
                span
                (
                    setClass('task-count'),
                    10
                ),
                ')'
            ),
            div
            (
                setID('tasksProgress'),
                setClass('progress'),
                div
                (
                    setClass('progress-bar primary'),
                )
            ),
            ul
            (
                setID('tasks'),
                setClass('nav nav-primary nav-stacked mt-5'),
                $taskGroup
            )
        )
    ),
);


render('pagebase');
