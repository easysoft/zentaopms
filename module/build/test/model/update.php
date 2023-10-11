#!/usr/bin/env php
<?php
/**

title=测试 buildModel->update();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';
zdTable('build')->config('build')->gen(20);
su('admin');

$buildIDList = array('1', '11');

$normalExecution = array('name' => '修改版本一', 'builder' => 'admin');
$executionBuild  = array('name' => '修改执行版本一', 'builder' => 'admin');
$noName          = array('name' => '', 'builder' => 'admin');
$noBuilder       = array('name' => '修改无创建者版本一', 'builder' => '');

$buildTester = new buildTest();
r($buildTester->updateTest($buildIDList[0], $normalExecution)) && p('0:field,old,new') && e('name,版本1,修改版本一');      // 修改项目版本
r($buildTester->updateTest($buildIDList[1], $executionBuild))  && p('0:field,old,new') && e('name,版本11,修改执行版本一'); // 修改执行版本
r($buildTester->updateTest($buildIDList[0], $noName))          && p('name:0')          && e('『名称编号』不能为空。');     // 名称为空测试
r($buildTester->updateTest($buildIDList[0], $noBuilder))       && p('builder:0')       && e('『构建者』不能为空。');       // 构建者为空测试

