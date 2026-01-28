#!/usr/bin/env php
<?php

/**

title=测试 programplanModel::getPairs();
timeout=0
cid=17745

- 步骤1：测试获取执行11产品11的all类型计划键值对 @/项目11
- 步骤2：测试获取执行12产品12的leaf类型计划键值对 @/项目12
- 步骤3：测试获取执行13产品0的计划键值对 @/项目13
- 步骤4：测试获取无效执行ID的计划键值对 @0
- 步骤5：测试获取执行15产品15的计划键值对 @/项目15

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('getpairs')->gen(10);
zenData('projectproduct')->loadYaml('getprojectproduct')->gen(20);

$executionIDList = array(11, 12, 13, 14, 15);
$productIDList   = array(11, 12, 13, 14, 15);

$programplan = new programplanModelTest();

r($programplan->getPairsTest($executionIDList[0], $productIDList[0], 'all'))  && p() && e('/项目11'); // 步骤1：测试获取执行11产品11的all类型计划键值对
r($programplan->getPairsTest($executionIDList[1], $productIDList[1], 'leaf')) && p() && e('/项目12'); // 步骤2：测试获取执行12产品12的leaf类型计划键值对
r($programplan->getPairsTest($executionIDList[2], 0, 'all'))                  && p() && e('/项目13'); // 步骤3：测试获取执行13产品0的计划键值对
r($programplan->getPairsTest(999, $productIDList[0], 'all'))                  && p() && e('0');      // 步骤4：测试获取无效执行ID的计划键值对
r($programplan->getPairsTest($executionIDList[4], $productIDList[4], 'all'))  && p() && e('/项目15'); // 步骤5：测试获取执行15产品15的计划键值对