#!/usr/bin/env php
<?php
/**

title=测试 docModel->updateDocOrder();
cid=16160

- 检查ID为1的文档顺序 @3
- 检查ID为2的文档顺序 @1
- 检查ID为3的文档顺序 @8
- 检查ID为4的文档顺序 @9
- 检查ID为5的文档顺序 @2
- 检查ID为6的文档顺序 @10
- 检查ID为7的文档顺序 @5
- 检查ID为8的文档顺序 @7
- 检查ID为9的文档顺序 @6
- 检查ID为10的文档顺序 @4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

$docData = zenData('doc')->loadYaml('doc');
$docData->order->range('1-10');
$docData->gen(10);
zenData('user')->gen(5);
su('admin');

$sortedIdList = array(2, 5, 1, 10, 7, 9, 8, 3, 4, 6);

$docTester = new docTest();
$sortedDocList = $docTester->updateDocOrderTest($sortedIdList);
r($sortedDocList[1])  && p() && e('3');  // 检查ID为1的文档顺序
r($sortedDocList[2])  && p() && e('1');  // 检查ID为2的文档顺序
r($sortedDocList[3])  && p() && e('8');  // 检查ID为3的文档顺序
r($sortedDocList[4])  && p() && e('9');  // 检查ID为4的文档顺序
r($sortedDocList[5])  && p() && e('2');  // 检查ID为5的文档顺序
r($sortedDocList[6])  && p() && e('10'); // 检查ID为6的文档顺序
r($sortedDocList[7])  && p() && e('5');  // 检查ID为7的文档顺序
r($sortedDocList[8])  && p() && e('7');  // 检查ID为8的文档顺序
r($sortedDocList[9])  && p() && e('6');  // 检查ID为9的文档顺序
r($sortedDocList[10]) && p() && e('4');  // 检查ID为10的文档顺序
