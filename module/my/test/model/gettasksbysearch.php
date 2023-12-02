#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/my.class.php';

zdTable('task')->config('task')->gen('40');
zdTable('story')->gen('40');
zdTable('project')->config('program')->gen('80');
zdTable('taskteam')->gen('0');
zdTable('user')->gen('1');

su('admin');

/**

title=测试 myModel->getTasksBySearch();
cid=1
pid=1

获取task状态的项目 >> 批量任务三,wait

*/

$my    = new myTest();
$account  = array('admin', 'user5');
$limit    = array(0, 5);

global $tester;
$tester->session->set('workTaskQuery', "`name` like '%任务%'");
$tester->session->set('contributeTaskQuery', "`name` like '%任务%'");

$tasks1 = $my->getTasksBySearchTest($account[0], $limit[0]);
$tasks2 = $my->getTasksBySearchTest($account[0], $limit[1]);
$tasks3 = $my->getTasksBySearchTest($account[1], $limit[0]);
$tasks4 = $my->getTasksBySearchTest($account[1], $limit[1]);

r(implode(',', $tasks1)) && p() && e('37,33,29,25,21,17,13,9,5,1'); // 测试通过搜索获取用户 admin 的任务id
r(count($tasks1))        && p() && e('10');                         // 测试通过搜索获取用户 admin 的任务数量
r(implode(',', $tasks2)) && p() && e('37,33,29,25,21');             // 测试通过搜索获取用户 admin 的任务id 限制5
r(count($tasks2))        && p() && e('5');                          // 测试通过搜索获取用户 admin 的任务数量 限制5
r(implode(',', $tasks3)) && p() && e('0');                          // 测试通过搜索获取用户 user5 的任务id
r(count($tasks3))        && p() && e('0');                          // 测试通过搜索获取用户 user5 的任务数量
r(implode(',', $tasks4)) && p() && e('0');                          // 测试通过搜索获取用户 user5 的任务id 限制5
r(count($tasks4))        && p() && e('0');                          // 测试通过搜索获取用户 user5 的任务数量 限制5
