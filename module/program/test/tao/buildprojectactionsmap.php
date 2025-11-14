#!/usr/bin/env php
<?php

/**

title=测试 programTao::buildProjectActionsMap();
timeout=0
cid=17716

- 测试生成项目集id为1的操作按钮数据。第0条的name属性 @team
- 测试生成项目集id为1的操作按钮数据。第0条的name属性 @team
- 测试生成项目集id为2的操作按钮数据。第0条的name属性 @team
- 测试生成敏捷项目的操作按钮数据。第0条的name属性 @team
- 测试生成敏捷项目的操作按钮数据。第0条的name属性 @team

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

zenData('user')->gen(5);
zenData('project')->loadYaml('program')->gen(40);
su('admin');

$programTester = new programTest();
r($programTester->buildProjectActionsMapTest(11))  && p('0:name') && e('team'); // 测试生成项目集id为1的操作按钮数据。
r($programTester->buildProjectActionsMapTest(61))  && p('0:name') && e('team'); // 测试生成项目集id为1的操作按钮数据。
r($programTester->buildProjectActionsMapTest(60))  && p('0:name') && e('team'); // 测试生成项目集id为2的操作按钮数据。
r($programTester->buildProjectActionsMapTest(100)) && p('0:name') && e('team'); // 测试生成敏捷项目的操作按钮数据。
r($programTester->buildProjectActionsMapTest(101)) && p('0:name') && e('team'); // 测试生成敏捷项目的操作按钮数据。
