#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('project_createbuild')->gen(20);
zenData('bug')->loadYaml('bug_createbuild')->gen(10);
zenData('user')->gen(1);
zenData('build')->gen(1);
zenData('action')->gen(1);

su('admin');

/**

title=bugTao->createBuild();
timeout=0
cid=15352

- 测试解决版本执行11 解决方案为设计如此的时候创建一个版本属性name @bug新建的版本1
- 测试解决版本执行12 解决方案为设计如此的时候创建一个版本属性name @bug新建的版本2
- 测试解决版本执行11 解决方案为重复BUG 重复bugID1的时候创建一个版本属性name @bug新建的版本3
- 测试解决版本执行11 解决方案为重复BUG 重复bugID1的时候创建一个版本属性name @bug新建的版本4
- 测试解决版本执行11 解决方案为设计如此的时候创建一个名称为空的版本 @『新版本名称』不能为空。
- 测试解决版本执行12 解决方案为设计如此的时候创建一个版本执行为空的版本 @『所属看板』不能为空。
- 测试解决版本执行11 解决方案为设计如此的时候创建一个版本执行为空的版本 @『所属执行』不能为空。
- 测试解决bugID 11 解决方案为设计如此的时候创建一个同名版本 @『新版本名称』已经有『bug新建的版本1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

*/

$bugIdList = array(1, 2, 3, 4, 5, 6, 7, 8, 9);

$bydesignBug1 = new stdclass();
$bydesignBug1->buildExecution = 11;
$bydesignBug1->buildName      = 'bug新建的版本1';
$bydesignBug1->resolution     = 'bydesign';

$bydesignBug2 = new stdclass();
$bydesignBug2->buildExecution = 12;
$bydesignBug2->buildName      = 'bug新建的版本2';
$bydesignBug2->resolution     = 'bydesign';

$duplicateBug1 = new stdclass();
$duplicateBug1->buildExecution = 11;
$duplicateBug1->buildName      = 'bug新建的版本3';
$duplicateBug1->resolution     = 'duplicate';
$duplicateBug1->duplicateBug   = 1;

$duplicateBug2 = new stdclass();
$duplicateBug2->buildExecution = 12;
$duplicateBug2->buildName      = 'bug新建的版本4';
$duplicateBug2->resolution     = 'duplicate';
$duplicateBug2->duplicateBug   = 1;

$emptyBuildName = new stdclass();
$emptyBuildName->buildExecution = 11;
$emptyBuildName->buildName      = '';
$emptyBuildName->resolution     = 'bydesign';

$emptyBuildExecution = new stdclass();
$emptyBuildExecution->buildExecution = 0;
$emptyBuildExecution->buildName      = 'bug新建的版本2';
$emptyBuildExecution->resolution     = 'bydesign';

$bug = new bugModelTest();

r($bug->createBuildTest($bydesignBug1,        $bugIdList[0])) && p('name') && e('bug新建的版本1');           // 测试解决版本执行11 解决方案为设计如此的时候创建一个版本
r($bug->createBuildTest($bydesignBug2,        $bugIdList[1])) && p('name') && e('bug新建的版本2');           // 测试解决版本执行12 解决方案为设计如此的时候创建一个版本
r($bug->createBuildTest($duplicateBug1,       $bugIdList[2])) && p('name') && e('bug新建的版本3');           // 测试解决版本执行11 解决方案为重复BUG 重复bugID1的时候创建一个版本
r($bug->createBuildTest($duplicateBug2,       $bugIdList[3])) && p('name') && e('bug新建的版本4');           // 测试解决版本执行11 解决方案为重复BUG 重复bugID1的时候创建一个版本
r($bug->createBuildTest($emptyBuildName,      $bugIdList[4])) && p()       && e('『新版本名称』不能为空。'); // 测试解决版本执行11 解决方案为设计如此的时候创建一个名称为空的版本
r($bug->createBuildTest($emptyBuildExecution, $bugIdList[5])) && p()       && e('『所属看板』不能为空。');   // 测试解决版本执行12 解决方案为设计如此的时候创建一个版本执行为空的版本
r($bug->createBuildTest($emptyBuildExecution, $bugIdList[6])) && p()       && e('『所属执行』不能为空。');   // 测试解决版本执行11 解决方案为设计如此的时候创建一个版本执行为空的版本

r($bug->createBuildTest($bydesignBug1, $bugIdList[0])) && p() && e('『新版本名称』已经有『bug新建的版本1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 测试解决bugID 11 解决方案为设计如此的时候创建一个同名版本
