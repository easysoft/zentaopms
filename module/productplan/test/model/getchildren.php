#!/usr/bin/env php
<?php
/**

title=productpanModel->getChildren();
timeout=0
cid=17632

- 测试传入空的PlanID获取子计划
 - 第3条的title属性 @计划3
 - 第3条的parent属性 @0
- 测试传入PlanID=1获取子计划
 - 第2条的title属性 @计划2
 - 第2条的parent属性 @1
- 测试传入子计划ID=2获取子计划 @0
- 测试传入普通计划ID=3获取子计划 @0
- 测试传入不存在计划ID=11获取子计划 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplan.unittest.class.php';

zenData('user')->gen(5);
zenData('productplan')->loadYaml('productplan')->gen(5);
su('admin');

$planIdList = array(0, 1, 2, 3, 11);

global $tester, $app;
$app->moduleName = 'productplan';
$app->rawModule = 'productplan';
$tester->loadModel('productplan');

r($tester->productplan->getChildren($planIdList[0])) && p('3:title,parent') && e('计划3,0'); // 测试传入空的PlanID获取子计划
r($tester->productplan->getChildren($planIdList[1])) && p('2:title,parent') && e('计划2,1'); // 测试传入PlanID=1获取子计划
r($tester->productplan->getChildren($planIdList[2])) && p()                 && e('0');       // 测试传入子计划ID=2获取子计划
r($tester->productplan->getChildren($planIdList[3])) && p()                 && e('0');       // 测试传入普通计划ID=3获取子计划
r($tester->productplan->getChildren($planIdList[4])) && p()                 && e('0');       // 测试传入不存在计划ID=11获取子计划
