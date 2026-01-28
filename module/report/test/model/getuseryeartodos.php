#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('todo')->loadYaml('todo_year')->gen(50);
zenData('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getUserYearTodos();
cid=18180
pid=1

*/
$accounts = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'), array());

$report = new reportModelTest();

r($report->getUserYearTodosTest($accounts[0])) && p() && e('count:2;undone:2;done:0;');    // 测试获取本年度 admin 的待办数
r($report->getUserYearTodosTest($accounts[1])) && p() && e('count:0;undone:;done:;');      // 测试获取本年度 dev17 的待办数
r($report->getUserYearTodosTest($accounts[2])) && p() && e('count:0;undone:;done:;');      // 测试获取本年度 dev20 的待办数
r($report->getUserYearTodosTest($accounts[3])) && p() && e('count:2;undone:2;done:0;');    // 测试获取本年度 admin dev17 的待办数
r($report->getUserYearTodosTest($accounts[4])) && p() && e('count:2;undone:2;done:0;');    // 测试获取本年度 admin dev20 的待办数
r($report->getUserYearTodosTest($accounts[5])) && p() && e('count:50;undone:38;done:12;'); // 测试获取本年度 所有 的待办数
