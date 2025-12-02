#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/setting.unittest.class.php';
su('admin');

/**

title=测试 settingModel->setItem();
timeout=0
cid=18367

- 设置没有vision的空数据 @0
- 设置没有vision的my模块的系统数据 @0
- 设置没有vision的project模块的系统数据 @test12
- 设置没有vision的product模块的系统数据 @test13
- 设置没有vision的所有者是admin的数据 @0
- 设置没有vision的common模块的admin配置数据 @0
- 设置没有vision的project模块的admin配置数据 @test16
- 设置没有vision的product模块的admin配置数据 @test17
- 设置有vision的所有者是system的数据 @0
- 设置有vision的my模块的系统数据 @0
- 设置有vision的project模块的系统数据 @test12
- 设置有vision的product模块的系统数据 @test13
- 设置有vision的所有者是admin的数据 @0
- 设置有vision的common模块的admin配置数据 @0
- 设置有vision的project模块的admin配置数据 @test16
- 设置有vision的product模块的admin配置数据 @test17

*/

$setting = new settingTest();

$pathList     = array();
$pathList[0]  = "";
$pathList[1]  = "system.my";
$pathList[2]  = "system.project.list";
$pathList[3]  = "system.product.global.story";
$pathList[4]  = "admin";
$pathList[5]  = "admin.common";
$pathList[6]  = "admin.project.list";
$pathList[7]  = "admin.product.global.story";
$pathList[8]  = "system@rnd";
$pathList[9]  = "system.my@rnd";
$pathList[10] = "system.project.list@rnd";
$pathList[11] = "system.product.global.story@rnd";
$pathList[12] = "admin@rnd";
$pathList[13] = "admin.common@rnd";
$pathList[14] = "admin.project.list@rnd";
$pathList[15] = "admin.product.global.story@rnd";

r($setting->setItemTest($pathList[0],  'test0'))  && p() && e('0');      // 设置没有vision的空数据
r($setting->setItemTest($pathList[1],  'test3'))  && p() && e('0');      // 设置没有vision的my模块的系统数据
r($setting->setItemTest($pathList[2],  'test12')) && p() && e('test12'); // 设置没有vision的project模块的系统数据
r($setting->setItemTest($pathList[3],  'test13')) && p() && e('test13'); // 设置没有vision的product模块的系统数据
r($setting->setItemTest($pathList[4],  'test6'))  && p() && e('0');      // 设置没有vision的所有者是admin的数据
r($setting->setItemTest($pathList[5],  'test7'))  && p() && e('0');      // 设置没有vision的common模块的admin配置数据
r($setting->setItemTest($pathList[6],  'test16')) && p() && e('test16'); // 设置没有vision的project模块的admin配置数据
r($setting->setItemTest($pathList[7],  'test17')) && p() && e('test17'); // 设置没有vision的product模块的admin配置数据
r($setting->setItemTest($pathList[8],  'test10')) && p() && e('0');      // 设置有vision的所有者是system的数据
r($setting->setItemTest($pathList[9],  'test11')) && p() && e('0');      // 设置有vision的my模块的系统数据
r($setting->setItemTest($pathList[10], 'test12')) && p() && e('test12'); // 设置有vision的project模块的系统数据
r($setting->setItemTest($pathList[11], 'test13')) && p() && e('test13'); // 设置有vision的product模块的系统数据
r($setting->setItemTest($pathList[12], 'test14')) && p() && e('0');      // 设置有vision的所有者是admin的数据
r($setting->setItemTest($pathList[13], 'test15')) && p() && e('0');      // 设置有vision的common模块的admin配置数据
r($setting->setItemTest($pathList[14], 'test16')) && p() && e('test16'); // 设置有vision的project模块的admin配置数据
r($setting->setItemTest($pathList[15], 'test17')) && p() && e('test17'); // 设置有vision的product模块的admin配置数据