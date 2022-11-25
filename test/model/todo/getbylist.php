#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/todo.class.php';
su('admin');

/**

title=测试 todoModel->getByList();
cid=1
pid=1

获取todo 1 2 3 4的名称 >> 自定义1的待办,BUG2的待办,任务3的待办,需求4的待办
获取todo 5 6 7 8的名称 >> 测试单5的待办,自定义6的待办,BUG7的待办,任务8的待办
获取todo 9 10 11 12的名称 >> 需求9的待办,测试单10的待办,自定义11的待办,BUG12的待办

*/

$todoIDList1 = array('1', '2', '3', '4');
$todoIDList2 = array('5', '6', '7', '8');
$todoIDList3 = array('9', '10', '11', '12');

$todo = new todoTest();

r($todo->getByListTest($todoIDList1)) && p() && e('自定义1的待办,BUG2的待办,任务3的待办,需求4的待办');      // 获取todo 1 2 3 4的名称
r($todo->getByListTest($todoIDList2)) && p() && e('测试单5的待办,自定义6的待办,BUG7的待办,任务8的待办');    // 获取todo 5 6 7 8的名称
r($todo->getByListTest($todoIDList3)) && p() && e('需求9的待办,测试单10的待办,自定义11的待办,BUG12的待办'); // 获取todo 9 10 11 12的名称