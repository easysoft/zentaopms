#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';

zdTable('user')->gen(5);
zdTable('project')->config('program')->gen(40);
su('admin');

/**

title=测试 programTao::buildProjectActionsMap();
timeout=0
cid=1

*/

$projectIdList = array(11, 60, 100);

$programTester = new programTest();
r($programTester->buildProjectActionsMapTest($projectIdList[0])) && p('0:name') && e('team'); // 测试生成项目集id为1的操作按钮数据。
r($programTester->buildProjectActionsMapTest($projectIdList[1])) && p('0:name') && e('team'); // 测试生成项目集id为2的操作按钮数据。
r($programTester->buildProjectActionsMapTest($projectIdList[2])) && p('0:name') && e('team'); // 测试生成敏捷项目的操作按钮数据。
