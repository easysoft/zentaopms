#!/usr/bin/env php
<?php

/**

title=测试 searchModel::getListCount();
timeout=0
cid=0

- 步骤1：只统计 task 和 bug 时返回 task 数量 @2
- 步骤2：只统计 task 和 bug 时返回 bug 数量 @1
- 步骤3：指定 feedback 类型时返回 feedback 数量 @1
- 步骤4：统计全部可访问类型时仍会过滤未来数据 @2
- 步骤5：统计全部可访问类型时仍会过滤 vision 不匹配数据 @1
- 步骤6：统计全部可访问类型时包含 feedback @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$tester->dao->delete()->from(TABLE_SEARCHINDEX)->exec();

$today = date('Y-m-d');
$day5  = date('Y-m-d', strtotime('-5 day'));
$day4  = date('Y-m-d', strtotime('-4 day'));
$day3  = date('Y-m-d', strtotime('-3 day'));
$day2  = date('Y-m-d', strtotime('-2 day'));
$day1  = date('Y-m-d', strtotime('-1 day'));
$tomorrow = date('Y-m-d', strtotime('+1 day'));

$rows = array(
    array('id' => 801, 'objectType' => 'task',     'objectID' => 1, 'title' => 'A', 'content' => 'a', 'addedDate' => "{$day5} 00:00:00", 'editedDate' => "{$day1} 00:00:00", 'vision' => 'rnd'),
    array('id' => 802, 'objectType' => 'task',     'objectID' => 2, 'title' => 'B', 'content' => 'b', 'addedDate' => "{$day4} 00:00:00", 'editedDate' => "{$day1} 00:00:00", 'vision' => 'rnd'),
    array('id' => 803, 'objectType' => 'bug',      'objectID' => 3, 'title' => 'C', 'content' => 'c', 'addedDate' => "{$day3} 00:00:00", 'editedDate' => "{$day1} 00:00:00", 'vision' => 'rnd'),
    array('id' => 804, 'objectType' => 'feedback', 'objectID' => 4, 'title' => 'D', 'content' => 'd', 'addedDate' => "{$day2} 00:00:00", 'editedDate' => "{$day1} 00:00:00", 'vision' => 'rnd'),
    array('id' => 805, 'objectType' => 'task',     'objectID' => 5, 'title' => 'E', 'content' => 'e', 'addedDate' => "{$tomorrow} 00:00:00", 'editedDate' => "{$day1} 00:00:00", 'vision' => 'rnd'),
    array('id' => 806, 'objectType' => 'bug',      'objectID' => 6, 'title' => 'F', 'content' => 'f', 'addedDate' => "{$day1} 00:00:00", 'editedDate' => "{$day1} 00:00:00", 'vision' => 'lite'),
);
foreach($rows as $row) $tester->dao->insert(TABLE_SEARCHINDEX)->data($row)->exec();

su('admin');

$search = new searchModelTest();

$taskBugCount         = $search->getListCountTest(array('task', 'bug'));
$taskBugFeedbackCount = $search->getListCountTest(array('feedback', 'task', 'bug'));
$allCount             = $search->getListCountTest('all');

r($taskBugCount['task'])               && p() && e('2'); // 步骤1：只统计 task 和 bug 时返回 task 数量
r($taskBugCount['bug'])                && p() && e('1'); // 步骤2：只统计 task 和 bug 时返回 bug 数量
r($taskBugFeedbackCount['feedback'])   && p() && e('1'); // 步骤3：指定 feedback 类型时返回 feedback 数量
r($allCount['task'])                   && p() && e('2'); // 步骤4：统计全部可访问类型时仍会过滤未来数据
r($allCount['bug'])                    && p() && e('1'); // 步骤5：统计全部可访问类型时仍会过滤 vision 不匹配数据
r($allCount['feedback'])               && p() && e('1'); // 步骤6：统计全部可访问类型时包含 feedback