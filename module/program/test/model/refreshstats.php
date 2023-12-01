#!/usr/bin/env php
<?php
/**

title=测试 programTao::refreshStats();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';

zdTable('project')->config('program')->gen(20);
zdTable('task')->config('task')->gen(20);
zdTable('team')->config('team')->gen(30);
zdTable('user')->gen(5);
su('admin');

$programTester = new programTest();
r($programTester->refreshStatsTest()) && p('1:progress') && e('25.00'); // 更新系统中项目、项目集的统计信息
