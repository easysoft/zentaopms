#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';
su('admin');

zenData('project')->loadYaml('program')->gen(30);

/**

title=测试 programModel::processNode();
timeout=0
cid=17706

*/

$programIdList = array(0, 1, 2);
$parentIdList  = array(0, 0, 1);
$oldPathList   = array(',0,', ',1,', ',2,');
$oldGradeList  = array(0, 1, 2);

$programTester = new programTest();

r($programTester->processNodeTest($programIdList[0], $parentIdList[0], $oldPathList[0], $oldGradeList[0])) && p('old,new') && e('0,0'); // 测试空数据
r($programTester->processNodeTest($programIdList[1], $parentIdList[1], $oldPathList[0], $oldGradeList[1])) && p('old,new') && e('0,0'); // 测试给定错误路径更新项目集1下所有子项的层级
r($programTester->processNodeTest($programIdList[1], $parentIdList[1], $oldPathList[1], $oldGradeList[0])) && p('old,new') && e('1,2'); // 测试给定错误层级更新项目集1下所有子项的层级
r($programTester->processNodeTest($programIdList[1], $parentIdList[1], $oldPathList[1], $oldGradeList[1])) && p('old,new') && e('2,2'); // 测试更新项目集1下所有子项的层级
r($programTester->processNodeTest($programIdList[2], $parentIdList[1], $oldPathList[0], $oldGradeList[1])) && p('old,new') && e('0,0'); // 测试给定错误路径更新项目集2下所有子项的层级
r($programTester->processNodeTest($programIdList[2], $parentIdList[1], $oldPathList[1], $oldGradeList[0])) && p('old,new') && e('2,2'); // 测试给定错误层级更新项目集2下所有子项的层级
r($programTester->processNodeTest($programIdList[2], $parentIdList[2], $oldPathList[2], $oldGradeList[2])) && p('old,new') && e('1,0'); // 测试更新项目集2下所有子项的层级
