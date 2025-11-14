#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');
zenData('project')->loadYaml('project')->gen(3);

/**

title=测试 projectModel->getSwitcher();
cid=17852

- 测试设置敏捷项目列表的1.5级下拉菜单 @0
- 测试设置瀑布项目列表的1.5级下拉菜单 @0
- 测试设置看板项目列表的1.5级下拉菜单 @0
- 测试设置敏捷项目文档空间的1.5级下拉菜单 @1
- 测试设置瀑布项目文档空间的1.5级下拉菜单 @1
- 测试设置看板项目文档空间的1.5级下拉菜单 @1
- 测试设置创建敏捷项目的1.5级下拉菜单 @1
- 测试设置创建瀑布项目的1.5级下拉菜单 @1
- 测试设置创建看板项目的1.5级下拉菜单 @1

*/

$projectIdList = range(1, 3);
$moduleList    = array('project', 'doc');
$methodList    = array('browse', 'projectSpace', 'create');

$projectTester = new projectTest();
r($projectTester->getSwitcherTest($projectIdList[0], $moduleList[0], $methodList[0])) && p() && e('0'); // 测试设置敏捷项目列表的1.5级下拉菜单
r($projectTester->getSwitcherTest($projectIdList[1], $moduleList[0], $methodList[0])) && p() && e('0'); // 测试设置瀑布项目列表的1.5级下拉菜单
r($projectTester->getSwitcherTest($projectIdList[2], $moduleList[0], $methodList[0])) && p() && e('0'); // 测试设置看板项目列表的1.5级下拉菜单
r($projectTester->getSwitcherTest($projectIdList[0], $moduleList[1], $methodList[1])) && p() && e('1'); // 测试设置敏捷项目文档空间的1.5级下拉菜单
r($projectTester->getSwitcherTest($projectIdList[1], $moduleList[1], $methodList[1])) && p() && e('1'); // 测试设置瀑布项目文档空间的1.5级下拉菜单
r($projectTester->getSwitcherTest($projectIdList[2], $moduleList[1], $methodList[1])) && p() && e('1'); // 测试设置看板项目文档空间的1.5级下拉菜单
r($projectTester->getSwitcherTest($projectIdList[0], $moduleList[1], $methodList[2])) && p() && e('1'); // 测试设置创建敏捷项目的1.5级下拉菜单
r($projectTester->getSwitcherTest($projectIdList[1], $moduleList[1], $methodList[2])) && p() && e('1'); // 测试设置创建瀑布项目的1.5级下拉菜单
r($projectTester->getSwitcherTest($projectIdList[2], $moduleList[1], $methodList[2])) && p() && e('1'); // 测试设置创建看板项目的1.5级下拉菜单
