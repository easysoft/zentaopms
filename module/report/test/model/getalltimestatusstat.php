#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/report.class.php';

zdTable('task')->gen(60);
zdTable('bug')->gen(60);
zdTable('story')->gen(60);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getAllTimeStatusStat();
cid=1
pid=1

*/

$report = new reportTest();

r($report->getAllTimeStatusStatTest()) && p('story') && e('changing:15;active:15;');                                 // 测试获取 需求 的状态分组个数
r($report->getAllTimeStatusStatTest()) && p('task')  && e('wait:10;doing:10;done:10;pause:10;cancel:10;closed:10;'); // 测试获取 任务 的状态分组个数
r($report->getAllTimeStatusStatTest()) && p('bug')   && e('active:40;resolved:10;');                                 // 测试获取 bug 的状态分组个数
