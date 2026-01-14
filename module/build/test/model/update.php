#!/usr/bin/env php
<?php

/**

title=测试 buildModel->update();
timeout=0
cid=15510

- 修改项目版本
 - 第0条的field属性 @name
 - 第0条的old属性 @版本1
 - 第0条的new属性 @修改版本一
- 修改执行版本
 - 第0条的field属性 @name
 - 第0条的old属性 @版本11
 - 第0条的new属性 @修改执行版本一
- 名称为空测试第name条的0属性 @『名称』不能为空。
- 构建者为空测试第builder条的0属性 @『构建者』不能为空。
- 修改重复名称第name条的0属性 @『名称』已经有『修改版本一』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('build')->loadYaml('build')->gen(20);
zenData('project')->loadYaml('project')->gen(100);
su('admin');

$buildIDList = array('1', '11');

$normalExecution = array('name' => '修改版本一', 'builder' => 'admin');
$executionBuild  = array('name' => '修改执行版本一', 'builder' => 'admin');
$noName          = array('name' => '', 'builder' => 'admin');
$noBuilder       = array('name' => '修改无创建者版本一', 'builder' => '');

$buildTester = new buildModelTest();
r($buildTester->updateTest($buildIDList[0], $normalExecution)) && p('0:field,old,new') && e('name,版本1,修改版本一');      // 修改项目版本
r($buildTester->updateTest($buildIDList[1], $executionBuild))  && p('0:field,old,new') && e('name,版本11,修改执行版本一'); // 修改执行版本
r($buildTester->updateTest($buildIDList[0], $noName))          && p('name:0')          && e('『名称』不能为空。');     // 名称为空测试
r($buildTester->updateTest($buildIDList[0], $noBuilder))       && p('builder:0')       && e('『构建者』不能为空。');       // 构建者为空测试
r($buildTester->updateTest($buildIDList[1], $normalExecution)) && p('name:0')          && e('『名称』已经有『修改版本一』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 修改重复名称