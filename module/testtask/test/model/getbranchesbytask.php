#!/usr/bin/env php
<?php
/**

title=测试 testtaskModel->getBranchesByTask();
timeout=0
cid=19152

- 测试获取联调测试单的分支数据
 -  @主干
 - 属性1 @分支1
 - 属性2 @分支2
- 测试获取分支测试单的分支数据
 -  @主干
 - 属性3 @分支3
- 测试获取主干测试单的分支数据 @主干

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testtaskproduct')->loadYaml('testtaskproduct')->gen(1);
zenData('build')->loadYaml('build')->gen(3);
zenData('branch')->loadYaml('branch')->gen(3);
zenData('user')->gen(5);

su('admin');

$jointTesttask = new stdclass();
$jointTesttask->id    = 1;
$jointTesttask->joint = 1;

$branchTesttask = new stdclass();
$branchTesttask->id    = 2;
$branchTesttask->joint = 0;
$branchTesttask->build = 2;

$mainTesttask = new stdclass();
$mainTesttask->id    = 3;
$mainTesttask->joint = 0;
$mainTesttask->build = 3;

$testtaskTest = new testtaskModelTest();
r($testtaskTest->getBranchesByTaskTest($jointTesttask, 1))  && p('0,1,2') && e('主干,分支1,分支2'); // 测试获取联调测试单的分支数据
r($testtaskTest->getBranchesByTaskTest($branchTesttask, 1)) && p('0,3')   && e('主干,分支3');       // 测试获取分支测试单的分支数据
r($testtaskTest->getBranchesByTaskTest($mainTesttask, 1))   && p('0')     && e('主干');             // 测试获取主干测试单的分支数据
