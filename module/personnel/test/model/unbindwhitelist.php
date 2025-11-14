#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/personnel.unittest.class.php';

/**

title=测试 personnelModel->unbindWhitelist();
cid=17338

- 测试id为0的用户解绑白名单 @0

- 测试项目集下id为1的用户解绑白名单 @test27

- 测试项目下id为3的用户解绑白名单 @test36

- 测试项目下id为5的用户解绑白名单 @test41

- 测试产品下id为9的用户解绑白名单 @test36

- 测试执行下id为11的用户解绑白名单 @test26

- 测试id为15的用户解绑白名单 @0

*/

zenData('acl')->loadYaml('acl')->gen(12);
zenData('project')->loadYaml('project')->gen(150);
zenData('product')->loadYaml('product')->gen(10);
zenData('projectproduct')->loadYaml('projectproduct')->gen(5);
zenData('userview')->gen(50);
zenData('user')->gen(50);

$personnelTester = new personnelTest('admin');

$idList = array(0, 1, 3, 5, 9, 11, 15);

r($personnelTester->unbindWhitelistTest($idList[0])) && p() && e('0');       // 测试id为0的用户解绑白名单
r($personnelTester->unbindWhitelistTest($idList[1])) && p() && e('test27'); // 测试项目集下id为1的用户解绑白名单
r($personnelTester->unbindWhitelistTest($idList[2])) && p() && e('test36'); // 测试项目下id为3的用户解绑白名单
r($personnelTester->unbindWhitelistTest($idList[3])) && p() && e('test41'); // 测试项目下id为5的用户解绑白名单
r($personnelTester->unbindWhitelistTest($idList[4])) && p() && e('test36'); // 测试产品下id为9的用户解绑白名单
r($personnelTester->unbindWhitelistTest($idList[5])) && p() && e('test26'); // 测试执行下id为11的用户解绑白名单
r($personnelTester->unbindWhitelistTest($idList[6])) && p() && e('0');       // 测试id为15的用户解绑白名单
