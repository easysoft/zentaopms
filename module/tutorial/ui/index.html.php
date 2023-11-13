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
            set::style(array('width' => '100%')),
            set('data-name', $key),
            a
            (
                set('data-name', $key),
                set::href('javascript:;'),
                set::className('btn-task pull-left'),
                span($i .'. '),
                span(
                    set::className('task-name'),
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
    $task['url'] = helper::createLink($task['nav']['module'], $task['nav']['method'], isset($task['nav']['vars']) ? $task['nav']['vars'] : '', 'tutorial');
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
    set::id('pageContainer'),
    div
    (
        set::id('iframeWrapper'),
        h::iframe
        (
            set::id('iframePage'),
            set::name('iframePage'),
            set::src(helper::createLink('index', 'index', 't=tutorial')),
        )
    ),
    div(set::id('taskModalBack')),
    div
    (
        set::id('taskModal'),
        div
        (
            set::className('close'),
            set::icon('close'),
        ),
        div
        (
            set::className('finish-all'),
            div
            (
                set::className('start-icon'),
                icon(
                    set::class('icon-front'),
                    'check-circle'
                ),
            ),
            h::h3($lang->tutorial->congratulation),
            btn
            (
                set::icon('restart'),
                set::className('btn btn-outline btn-reset-tasks'),
                $lang->tutorial->restart
            ),
            ' ',
            btn
            (
                set::className('btn btn-outline'),
                set::url(helper::createLink('tutorial', 'quit')),
                $lang->tutorial->exit
            )
        ),
        div
        (
            set::className('finish'),
            div
            (
                set::className('start-icon'),
                icon(
                    set::class('icon-front'),
                    'check-circle'
                ),
            ),
            h::h3(
                $lang->tutorial->congratulateTask,
                "【",
                span
                (
                    set::className('task-name-current'),
                ),
                "】！"
            ),
            btn
            (
                set::className('btn btn-outline btn-next-task btn-task'),
                $lang->tutorial->nextTask,
                set::trailingIcon('angle-right')
            )
        )
    ),
    div
    (
        set::id('sidebar'),
        h::header
        (
            set::className('bg-primary'),
            div
            (
                set::className('start-icon'),
            ),
            h::h2('新手教程'),
            div
            (
                set::className('actions'),
                btn
                (
                    set::className('size-sm'),
                    set::type('danger'),
                    set::url(helper::createLink('tutorial', 'quit')),
                    '退出教程',
                )
            )
        ),
        h::section
        (
            set::id('current'),
            h::h4('当前任务'),
            div
            (
                set::id('task'),
                set::className('panel'),
                div
                (
                    set::className('panel-heading bg-secondary'),
                    h::strong
                    (
                        span
                        (
                            set::className('task-id-current'),
                        ),
                        '. ',
                        span
                        (
                            set::className('task-name task-name-current'),
                        )
                    )
                ),
                div
                (
                    set::className('panel-body'),
                    div
                    (
                        set::className('task-desc'),
                        p(),
                        h::ul
                        (
                            li
                            (
                                set::data_target('nav'),
                                set::className('wait'),
                            ),
                            li
                            (
                                set::data_target('form'),
                                set::className('wait'),
                            ),
                            li
                            (
                                set::data_target('submit'),
                                set::className('wait'),
                            )
                        )
                    ),
                    h::a(
                        div
                        (
                            set::className('normal'),
                        ),
                        div
                        (
                            set::className('opened'),
                        ),
                        div
                        (
                            set::className('reload'),
                        ),
                    ),
                    div
                    (
                        set::className('alert warning-pale flex items-center'),
                        set::style(array('padding' => '5px', 'margin-bottom' => '0px')),
                        "教程任务中，数据不会保存。"
                    )
                )
            ),
            div
            (
                set::className('clearfix actions'),
                btn
                (
                    set::icon('arrow-left'),
                    set::size('sm'),
                    set::className('btn-prev-task btn-task circle'),
                    '上一个',
                ),
                btn
                (
                    set::trailingIcon('arrow-right'),
                    set::size('sm'),
                    set::type('primary'),
                    set::className('btn-next-task btn-task circle pull-right'),
                    '下一个任务',
                )
            )
        ),
        h::section
        (
            set::id('all'),
            h::h4(
                '所有任务(',
                span
                (
                    set::className('task-num-finish'),
                    5,
                ),
                '/',
                span
                (
                    set::className('task-count'),
                    10
                ),
                ')'
            ),
            div
            (
                set::id('tasksProgress'),
                set::className('progress'),
                div
                (
                    set::className('progress-bar primary'),
                    set::style(array('width' => '30%')),
                )
            ),
            h::ul
            (
                set::id('tasks'),
                set::className('nav nav-primary nav-stacked'),
                $taskGroup
            )
        )
    ),
);


render('pagebase');
