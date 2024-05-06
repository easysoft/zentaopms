#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

/**
title=测试Tao层的根据类型设置待办名称 todoTao::closeTodo()
timeout=0
cid=1
*/

global $tester;
$todoModel     = $tester->loadModel('todo');
$storyModel    = $tester->loadModel('story');
$taskModel     = $tester->loadModel('task');
$bugModel      = $tester->loadModel('bug');
$testtaskModel = $tester->loadModel('testtask');

/* Create a todo. */
zenData('todo')->loadYaml('settodonamebytype')->gen(4);

/* Create a simple story. */
$story = new stdclass();
$story->id       = 1;
$story->title    = 'simplestory';
$story->estimate = '1';
$tester->dao->exec('TRUNCATE TABLE ' . TABLE_STORY);
$tester->dao->insert(TABLE_STORY)->data($story)->exec();

/* Create a simple task. */
$task = new stdclass();
$task->id       = 1;
$task->name     = 'simpletask';
$task->estimate = '1';
$task->left     = '1';
$tester->dao->exec('TRUNCATE TABLE ' . TABLE_TASK);
$tester->dao->insert(TABLE_TASK)->data($task)->exec();

/* Create a simple bug. */
$bug = new stdclass();
$bug->id    = 1;
$bug->title = 'simplebug';
$tester->dao->exec('TRUNCATE TABLE ' . TABLE_BUG);
$tester->dao->insert(TABLE_BUG)->data($bug)->exec();

/* Create a simple testtask. */
$testtask = new stdclass();
$testtask->id   = 1;
$testtask->name = 'simpletesttask';
$tester->dao->exec('TRUNCATE TABLE ' . TABLE_TESTTASK);
$tester->dao->insert(TABLE_TESTTASK)->data($testtask)->exec();

r($todoModel->setTodoNameByType($todoModel->getByID(1))) && p('name') && e('simplestory');    // 验证关联故事的待办名称
r($todoModel->setTodoNameByType($todoModel->getByID(2))) && p('name') && e('simpletask');     // 验证关联任务的待办名称
r($todoModel->setTodoNameByType($todoModel->getByID(3))) && p('name') && e('simplebug');      // 验证关联缺陷的待办名称
r($todoModel->setTodoNameByType($todoModel->getByID(4))) && p('name') && e('simpletesttask'); // 验证关联测试单的待办名称
