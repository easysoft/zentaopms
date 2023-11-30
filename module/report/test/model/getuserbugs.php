#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/report.class.php';

zdTable('bug')->config('bug')->gen('100');
zdTable('user')->gen(10);

su('admin');

/**

title=测试 reportModel->getUserBugs();
cid=1
pid=1

*/

$report = new reportTest();

r($report->getUserBugsTest()) && p() && e('admin:29;user1:27;user2:28;'); // 获取人员 bug 数
