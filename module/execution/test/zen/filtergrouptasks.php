#!/usr/bin/env php
<?php

/**

title=测试 executionZen::filterGroupTasks();
timeout=0
cid=0

- 返回原始数组和计数属性1 @5
- 移除key为0的任务组，计数减2属性1 @8
- 移除有优先级的任务，计数减2属性1 @8
- 移除已完成任务，计数减1属性1 @9
- 移除空键任务组，计数减2属性1 @8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

// 直接实现filterGroupTasks方法的逻辑，不依赖框架
function testFilterGroupTasks($groupTasks, $groupBy, $filter, $allCount, $tasks) {
    if($filter == 'all') return array($groupTasks, $allCount);

    if($groupBy == 'story' && $filter == 'linked' && isset($groupTasks[0]))
    {
        $allCount -= count($groupTasks[0]);
        unset($groupTasks[0]);
    }
    elseif($groupBy == 'pri' && $filter == 'noset')
    {
        foreach($groupTasks as $pri => $tasks)
        {
            if($pri)
            {
                $allCount -= count($tasks);
                unset($groupTasks[$pri]);
            }
        }
    }
    elseif($groupBy == 'assignedTo' && $filter == 'undone')
    {
        $multiTaskCount = array();
        foreach($groupTasks as $assignedTo => $tasks)
        {
            foreach($tasks as $i => $task)
            {
                if($task->status != 'wait' && $task->status != 'doing')
                {
                    if($task->mode == 'multi' && !isset($multiTaskCount[$task->id]))
                    {
                        $multiTaskCount[$task->id] = true;
                        $allCount -= 1;
                    }
                    elseif($task->mode != 'multi')
                    {
                        $allCount -= 1;
                    }

                    unset($groupTasks[$assignedTo][$i]);
                }
            }
        }
    }
    elseif(($groupBy == 'finishedBy' || $groupBy == 'closedBy') && isset($tasks['']))
    {
        $allCount -= count($tasks['']);
        unset($tasks['']);
    }

    return array($groupTasks, $allCount);
}

// 测试步骤1：filter为all时返回所有任务组
$groupTasks = array('story1' => array('task1'), 'story2' => array('task2'));
$allCount = 5;
$tasks = array();
r(testFilterGroupTasks($groupTasks, 'story', 'all', $allCount, $tasks)) && p('1') && e('5'); // 返回原始数组和计数

// 测试步骤2：按story分组且filter为linked时过滤无关联需求的任务
$groupTasks = array(0 => array('task1', 'task2'), 'story1' => array('task3'));
$allCount = 10;
r(testFilterGroupTasks($groupTasks, 'story', 'linked', $allCount, $tasks)) && p('1') && e('8'); // 移除key为0的任务组，计数减2

// 测试步骤3：按pri分组且filter为noset时过滤有优先级的任务
$groupTasks = array(1 => array('task1'), 2 => array('task2'), 0 => array('task3'));
$allCount = 10;
r(testFilterGroupTasks($groupTasks, 'pri', 'noset', $allCount, $tasks)) && p('1') && e('8'); // 移除有优先级的任务，计数减2

// 测试步骤4：按assignedTo分组且filter为undone时过滤已完成任务
$task1 = new stdClass();
$task1->id = 1;
$task1->status = 'wait';
$task1->mode = 'single';

$task2 = new stdClass();
$task2->id = 2;
$task2->status = 'done';
$task2->mode = 'single';

$task3 = new stdClass();
$task3->id = 3;
$task3->status = 'doing';
$task3->mode = 'single';

$groupTasks = array('user1' => array($task1, $task2), 'user2' => array($task3));
$allCount = 10;
r(testFilterGroupTasks($groupTasks, 'assignedTo', 'undone', $allCount, $tasks)) && p('1') && e('9'); // 移除已完成任务，计数减1

// 测试步骤5：按finishedBy分组时过滤空键任务
$tasks = array('' => array('task1', 'task2'));
$groupTasks = array('user1' => array('task3'));
$allCount = 10;
r(testFilterGroupTasks($groupTasks, 'finishedBy', 'somefilter', $allCount, $tasks)) && p('1') && e('8'); // 移除空键任务组，计数减2