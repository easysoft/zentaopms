#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/report.class.php';

zdTable('project')->config('execution')->gen(10);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getProjectExecutions();
cid=1
pid=1

*/

$report = new reportTest();

$names = $report->getProjectExecutionsTest();

r(implode(',', array_keys($names))) && p() && e('101,102,103,104,105,106'); // 获取项目执行id

r(implode(',', $names)) && p() && e('敏捷项目1/迭代5,敏捷项目1/迭代6,敏捷项目1/迭代7,敏捷项目1,敏捷项目1,瀑布项目2/阶段10'); // 获取项目执行名称

r(count($names)) && p() && e('6'); // 获取项目执行数量
