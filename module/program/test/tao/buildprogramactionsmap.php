#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

zenData('user')->gen(5);
zenData('project')->loadYaml('program')->gen(40);
su('admin');

/**

title=测试 programTao::buildProgramActionsMap();
timeout=0
cid=1

- 测试生成项目集id为1的操作按钮数据。第0条的name属性 @close
- 测试生成项目集id为2的操作按钮数据。第0条的name属性 @close
- 测试生成敏捷项目的操作按钮数据。第0条的name属性 @close
- 测试生成瀑布项目的操作按钮数据。第0条的name属性 @close
- 测试生成看板项目的操作按钮数据。第0条的name属性 @close

*/

$objectIdList = array(1, 2, 11, 60, 100);

$programTester = new programTest();
r($programTester->buildProgramActionsMapTest($objectIdList[0])) && p('0:name') && e('close'); // 测试生成项目集id为1的操作按钮数据。
r($programTester->buildProgramActionsMapTest($objectIdList[1])) && p('0:name') && e('close'); // 测试生成项目集id为2的操作按钮数据。
r($programTester->buildProgramActionsMapTest($objectIdList[2])) && p('0:name') && e('close'); // 测试生成敏捷项目的操作按钮数据。
r($programTester->buildProgramActionsMapTest($objectIdList[3])) && p('0:name') && e('close'); // 测试生成瀑布项目的操作按钮数据。
r($programTester->buildProgramActionsMapTest($objectIdList[4])) && p('0:name') && e('close'); // 测试生成看板项目的操作按钮数据。
