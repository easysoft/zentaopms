#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/setting.class.php';
su('admin');

/**

title=测试 settingModel->setItem();
cid=1
pid=1

设置没有vision的空数据 >> 0
设置没有vision的只有视图的数据 >> 0
设置没有vision的所有者是system的数据 >> 0
设置没有vision的my模块的系统数据 >> 0
设置没有vision的project模块的系统数据 >> test4
设置没有vision的product模块的系统数据 >> test5
设置没有vision的所有者是admin的数据 >> 0
设置没有vision的common模块的admin配置数据 >> 0
设置没有vision的project模块的admin配置数据 >> test8
设置没有vision的product模块的admin配置数据 >> test9
设置有vision的所有者是system的数据 >> 0
设置有vision的my模块的系统数据 >> 0
设置有vision的project模块的系统数据 >> test12
设置有vision的product模块的系统数据 >> test13
设置有vision的所有者是admin的数据 >> 0
设置有vision的common模块的admin配置数据 >> 0
设置有vision的project模块的admin配置数据 >> test16
设置有vision的product模块的admin配置数据 >> test17

*/

$setting = new settingTest();

$pathList     = array();
$pathList[0]  = "";
$pathList[1]  = "@rnd";
$pathList[2]  = "system";
$pathList[3]  = "system.my";
$pathList[4]  = "system.project.list";
$pathList[5]  = "system.product.global.story";
$pathList[6]  = "admin";
$pathList[7]  = "admin.common";
$pathList[8]  = "admin.project.list";
$pathList[9]  = "admin.product.global.story";
$pathList[10] = "system@rnd";
$pathList[11] = "system.my@rnd";
$pathList[12] = "system.project.list@rnd";
$pathList[13] = "system.product.global.story@rnd";
$pathList[14] = "admin@rnd";
$pathList[15] = "admin.common@rnd";
$pathList[16] = "admin.project.list@rnd";
$pathList[17] = "admin.product.global.story@rnd";

r($setting->setItemTest($pathList[0],  'test0'))  && p() && e('0');      // 设置没有vision的空数据
r($setting->setItemTest($pathList[1],  'test1'))  && p() && e('0');      // 设置没有vision的只有视图的数据
r($setting->setItemTest($pathList[2],  'test2'))  && p() && e('0');      // 设置没有vision的所有者是system的数据
r($setting->setItemTest($pathList[3],  'test3'))  && p() && e('0');      // 设置没有vision的my模块的系统数据
r($setting->setItemTest($pathList[4],  'test4'))  && p() && e('test4');  // 设置没有vision的project模块的系统数据
r($setting->setItemTest($pathList[5],  'test5'))  && p() && e('test5');  // 设置没有vision的product模块的系统数据
r($setting->setItemTest($pathList[6],  'test6'))  && p() && e('0');      // 设置没有vision的所有者是admin的数据
r($setting->setItemTest($pathList[7],  'test7'))  && p() && e('0');      // 设置没有vision的common模块的admin配置数据
r($setting->setItemTest($pathList[8],  'test8'))  && p() && e('test8');  // 设置没有vision的project模块的admin配置数据
r($setting->setItemTest($pathList[9],  'test9'))  && p() && e('test9');  // 设置没有vision的product模块的admin配置数据
r($setting->setItemTest($pathList[10], 'test10')) && p() && e('0');      // 设置有vision的所有者是system的数据
r($setting->setItemTest($pathList[11], 'test11')) && p() && e('0');      // 设置有vision的my模块的系统数据
r($setting->setItemTest($pathList[12], 'test12')) && p() && e('test12'); // 设置有vision的project模块的系统数据
r($setting->setItemTest($pathList[13], 'test13')) && p() && e('test13'); // 设置有vision的product模块的系统数据
r($setting->setItemTest($pathList[14], 'test14')) && p() && e('0');      // 设置有vision的所有者是admin的数据
r($setting->setItemTest($pathList[15], 'test15')) && p() && e('0');      // 设置有vision的common模块的admin配置数据
r($setting->setItemTest($pathList[16], 'test16')) && p() && e('test16'); // 设置有vision的project模块的admin配置数据
r($setting->setItemTest($pathList[17], 'test17')) && p() && e('test17'); // 设置有vision的product模块的admin配置数据

