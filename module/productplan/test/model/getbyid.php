#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('productplan')->loadYaml('productplan')->gen(5);
zenData('user')->gen(5);
su('admin');

/**

title=测试productplanModel->getByID();
timeout=0
cid=17629

- 测试传入空值，且不设置图片大小 @0
- 测试获取计划2，且不设置图片大小属性title @计划2
- 测试获取不存在的计划，且不设置图片大小 @0
- 测试传入空值，且设置图片大小 @0
- 测试获取计划2，且设置图片大小属性title @计划2
- 测试获取不存在的计划，且设置图片大小 @0

*/

$planIdList     = array(0, 2, 10);
$setImgSizeList = array(false, true);

global $tester, $app;
$app->rawModule  = 'productplan';
$app->rawMethod  = 'browse';
$app->moduleName = 'productplan';
$app->methodName = 'browse';
$tester->loadModel('productplan');
r($tester->productplan->getByID($planIdList[0], $setImgSizeList[0])) && p()        && e('0');     // 测试传入空值，且不设置图片大小
r($tester->productplan->getByID($planIdList[1], $setImgSizeList[0])) && p('title') && e('计划2'); // 测试获取计划2，且不设置图片大小
r($tester->productplan->getByID($planIdList[2], $setImgSizeList[0])) && p()        && e('0');     // 测试获取不存在的计划，且不设置图片大小
r($tester->productplan->getByID($planIdList[0], $setImgSizeList[1])) && p()        && e('0');     // 测试传入空值，且设置图片大小
r($tester->productplan->getByID($planIdList[1], $setImgSizeList[1])) && p('title') && e('计划2'); // 测试获取计划2，且设置图片大小
r($tester->productplan->getByID($planIdList[2], $setImgSizeList[1])) && p()        && e('0');     // 测试获取不存在的计划，且设置图片大小
