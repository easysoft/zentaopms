#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getAllTimeStatusStat();
cid=1
pid=1

测试获取 需求 的状态分组个数 >> changed:81;active:93;draft:51;
测试获取 任务 的状态分组个数 >> wait:152;doing:152;done:152;pause:152;cancel:151;closed:151;
测试获取 bug 的状态分组个数 >> active:135;resolved:90;closed:60;

*/

$report = new reportTest();

r($report->getAllTimeStatusStatTest()) && p('story') && e('changed:81;active:93;draft:51;');                               // 测试获取 需求 的状态分组个数
r($report->getAllTimeStatusStatTest()) && p('task')  && e('wait:152;doing:152;done:152;pause:152;cancel:151;closed:151;'); // 测试获取 任务 的状态分组个数
r($report->getAllTimeStatusStatTest()) && p('bug')   && e('active:135;resolved:90;closed:60;');                            // 测试获取 bug 的状态分组个数