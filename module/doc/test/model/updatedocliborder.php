#!/usr/bin/env php
<?php
/**

title=测试 docModel->updateDocLibOrder();
cid=16159

- 测试更新ID为0的文档库顺序为2 @0
- 测试更新ID为1的文档库顺序为5 @5
- 测试更新ID为2的文档库顺序为1 @1
- 测试更新ID为3的文档库顺序为10 @10
- 测试更新ID为4的文档库顺序为7 @7
- 测试更新ID为5的文档库顺序为9 @9
- 测试更新ID不存在的文档库顺序为8 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

$docLibData = zenData('doclib')->loadYaml('doclib');
$docLibData->order->range('1-5');
$docLibData->gen(5);
zenData('user')->gen(5);
su('admin');

$idList    = array(0, 1, 2, 3, 4, 5, 6);
$orderList = array(2, 5, 1, 10, 7, 9, 8);

$docLibTester = new docTest();
r($docLibTester->updateDoclibOrderTest($idList[0], $orderList[0])) && p() && e('0');  // 测试更新ID为0的文档库顺序为2
r($docLibTester->updateDoclibOrderTest($idList[1], $orderList[1])) && p() && e('5');  // 测试更新ID为1的文档库顺序为5
r($docLibTester->updateDoclibOrderTest($idList[2], $orderList[2])) && p() && e('1');  // 测试更新ID为2的文档库顺序为1
r($docLibTester->updateDoclibOrderTest($idList[3], $orderList[3])) && p() && e('10'); // 测试更新ID为3的文档库顺序为10
r($docLibTester->updateDoclibOrderTest($idList[4], $orderList[4])) && p() && e('7');  // 测试更新ID为4的文档库顺序为7
r($docLibTester->updateDoclibOrderTest($idList[5], $orderList[5])) && p() && e('9');  // 测试更新ID为5的文档库顺序为9
r($docLibTester->updateDoclibOrderTest($idList[6], $orderList[6])) && p() && e('0');  // 测试更新ID不存在的文档库顺序为8
