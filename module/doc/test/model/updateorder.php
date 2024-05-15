#!/usr/bin/env php
<?php

/**

title=测试 docModel->updateOrder();
timeout=0
cid=1

- 测试ID为1的目录，order值不变的情况 @10
- 测试ID为2的目录，order值需要更新的情况 @1
- 测试目录ID不存在的情况 @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('module')->loadYaml('module')->gen(10);

$catalogOrderList   = array();
$catalogOrderList[] = array('id' => 1,  'order' => 10);
$catalogOrderList[] = array('id' => 2,  'order' => 1);
$catalogOrderList[] = array('id' => 11, 'order' => 100);

$docTester = new docTest();
r($docTester->updateOrderTest($catalogOrderList[0]['id'], $catalogOrderList[0]['order'])) && p() && e(10);    // 测试ID为1的目录，order值不变的情况
r($docTester->updateOrderTest($catalogOrderList[1]['id'], $catalogOrderList[1]['order'])) && p() && e(1);     // 测试ID为2的目录，order值需要更新的情况
r($docTester->updateOrderTest($catalogOrderList[2]['id'], $catalogOrderList[2]['order'])) && p() && e('~~');  // 测试目录ID不存在的情况
