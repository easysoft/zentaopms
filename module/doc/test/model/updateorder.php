#!/usr/bin/env php
<?php

/**

title=测试 docModel->updateOrder();
timeout=0
cid=16162

- 测试ID为1的目录，order值不变的情况 @10
- 测试ID为2的目录，order值需要更新的情况 @1
- 测试ID为3的目录，order值不变的情况 @30
- 测试ID为4的目录，order值需要更新的情况 @2
- 测试目录ID不存在的情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('module')->loadYaml('module')->gen(10);

$catalogOrderList   = array();
$catalogOrderList[] = array('id' => 1,  'order' => 10);
$catalogOrderList[] = array('id' => 2,  'order' => 1);
$catalogOrderList[] = array('id' => 3,  'order' => 30);
$catalogOrderList[] = array('id' => 4,  'order' => 2);
$catalogOrderList[] = array('id' => 11, 'order' => 100);

$docTester = new docTest();
r($docTester->updateOrderTest($catalogOrderList[0]['id'], $catalogOrderList[0]['order'])) && p() && e(10);  // 测试ID为1的目录，order值不变的情况
r($docTester->updateOrderTest($catalogOrderList[1]['id'], $catalogOrderList[1]['order'])) && p() && e(1);   // 测试ID为2的目录，order值需要更新的情况
r($docTester->updateOrderTest($catalogOrderList[2]['id'], $catalogOrderList[2]['order'])) && p() && e(30);  // 测试ID为3的目录，order值不变的情况
r($docTester->updateOrderTest($catalogOrderList[3]['id'], $catalogOrderList[3]['order'])) && p() && e(2);   // 测试ID为4的目录，order值需要更新的情况
r($docTester->updateOrderTest($catalogOrderList[4]['id'], $catalogOrderList[4]['order'])) && p() && e('0'); // 测试目录ID不存在的情况
