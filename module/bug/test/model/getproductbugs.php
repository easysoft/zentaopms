#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

/**

title=bugModel->getProductBugs();
timeout=0
cid=15389

- 测试获取产品ID为1 2 3 的bug @1,2,3,4,5,6,7,8,9

- 测试获取产品ID为1 2 3 的bug @1,2,3,4,5,6,7,8,9

- 测试获取产品ID为1 2 3 解决日期为今天的bug @4
- 测试获取产品ID为1 2 3 解决日期大于上周 小于下周的bug @3,4,5,9

- 测试获取产品ID为1 2 3 创建日期为今天的bug @5
- 测试获取产品ID为1 2 3 创建日期大于上周 小于下周的bug @4,5,6

- 测试获取产品ID为4 5 6 的bug @10,11,12,13,14,15,16,17,18

- 测试获取产品ID为4 5 6 的bug @10,11,12,13,14,15,16,17,18

- 测试获取产品ID为4 5 6 解决日期为今天的bug @10,16

- 测试获取产品ID为4 5 6 解决日期大于上周 小于下周的bug @10,11,15,16,17

- 测试获取产品ID为4 5 6 创建日期为今天的bug @13
- 测试获取产品ID为4 5 6 创建日期大于上周 小于下周的bug @12,13,14

- 测试获取产品ID为7 8 9 的bug @19,20,21,22,23,24,25,26,27

- 测试获取产品ID为7 8 9 的bug @19,20,21,22,23,24,25,26,27

- 测试获取产品ID为7 8 9 解决日期为今天的bug @22
- 测试获取产品ID为7 8 9 解决日期大于上周 小于下周的bug @21,22,23,27

- 测试获取产品ID为7 8 9 创建日期为今天的bug @21
- 测试获取产品ID为7 8 9 创建日期大于上周 小于下周的bug @20,21,22

- 测试获取产品ID为0的bug @0
- 测试获取产品ID为0的bug @0
- 测试获取产品ID为0解决日期为今天的bug @0
- 测试获取产品ID为0解决日期大于上周 小于下周的bug @0
- 测试获取产品ID为0创建日期为今天的bug @0
- 测试获取产品ID为0创建日期大于上周 小于下周的bug @0

*/

zenData('product')->gen(10);
zenData('bug')->loadYaml('bug_product')->gen(40);

$productIdList = array('1,2,3', '4,5,6', '7,8,9', '0');
$typeList      = array('', 'resolved', 'opened');
$beginList     = array('today', 'lastweek');
$endList       = array('today', 'nextweek');

$bug = new bugTest();

r($bug->getProductBugsTest($productIdList[0], $typeList[0], $beginList[0], $endList[0])) && p() && e('1,2,3,4,5,6,7,8,9');          // 测试获取产品ID为1 2 3 的bug
r($bug->getProductBugsTest($productIdList[0], $typeList[0], $beginList[1], $endList[1])) && p() && e('1,2,3,4,5,6,7,8,9');          // 测试获取产品ID为1 2 3 的bug
r($bug->getProductBugsTest($productIdList[0], $typeList[1], $beginList[0], $endList[0])) && p() && e('4');                          // 测试获取产品ID为1 2 3 解决日期为今天的bug
r($bug->getProductBugsTest($productIdList[0], $typeList[1], $beginList[1], $endList[1])) && p() && e('3,4,5,9');                    // 测试获取产品ID为1 2 3 解决日期大于上周 小于下周的bug
r($bug->getProductBugsTest($productIdList[0], $typeList[2], $beginList[0], $endList[0])) && p() && e('5');                          // 测试获取产品ID为1 2 3 创建日期为今天的bug
r($bug->getProductBugsTest($productIdList[0], $typeList[2], $beginList[1], $endList[1])) && p() && e('4,5,6');                      // 测试获取产品ID为1 2 3 创建日期大于上周 小于下周的bug

r($bug->getProductBugsTest($productIdList[1], $typeList[0], $beginList[0], $endList[0])) && p() && e('10,11,12,13,14,15,16,17,18'); // 测试获取产品ID为4 5 6 的bug
r($bug->getProductBugsTest($productIdList[1], $typeList[0], $beginList[1], $endList[1])) && p() && e('10,11,12,13,14,15,16,17,18'); // 测试获取产品ID为4 5 6 的bug
r($bug->getProductBugsTest($productIdList[1], $typeList[1], $beginList[0], $endList[0])) && p() && e('10,16');                      // 测试获取产品ID为4 5 6 解决日期为今天的bug
r($bug->getProductBugsTest($productIdList[1], $typeList[1], $beginList[1], $endList[1])) && p() && e('10,11,15,16,17');             // 测试获取产品ID为4 5 6 解决日期大于上周 小于下周的bug
r($bug->getProductBugsTest($productIdList[1], $typeList[2], $beginList[0], $endList[0])) && p() && e('13');                         // 测试获取产品ID为4 5 6 创建日期为今天的bug
r($bug->getProductBugsTest($productIdList[1], $typeList[2], $beginList[1], $endList[1])) && p() && e('12,13,14');                   // 测试获取产品ID为4 5 6 创建日期大于上周 小于下周的bug

r($bug->getProductBugsTest($productIdList[2], $typeList[0], $beginList[0], $endList[0])) && p() && e('19,20,21,22,23,24,25,26,27'); // 测试获取产品ID为7 8 9 的bug
r($bug->getProductBugsTest($productIdList[2], $typeList[0], $beginList[1], $endList[1])) && p() && e('19,20,21,22,23,24,25,26,27'); // 测试获取产品ID为7 8 9 的bug
r($bug->getProductBugsTest($productIdList[2], $typeList[1], $beginList[0], $endList[0])) && p() && e('22');                         // 测试获取产品ID为7 8 9 解决日期为今天的bug
r($bug->getProductBugsTest($productIdList[2], $typeList[1], $beginList[1], $endList[1])) && p() && e('21,22,23,27');                // 测试获取产品ID为7 8 9 解决日期大于上周 小于下周的bug
r($bug->getProductBugsTest($productIdList[2], $typeList[2], $beginList[0], $endList[0])) && p() && e('21');                         // 测试获取产品ID为7 8 9 创建日期为今天的bug
r($bug->getProductBugsTest($productIdList[2], $typeList[2], $beginList[1], $endList[1])) && p() && e('20,21,22');                   // 测试获取产品ID为7 8 9 创建日期大于上周 小于下周的bug

r($bug->getProductBugsTest($productIdList[3], $typeList[0], $beginList[0], $endList[0])) && p() && e('0');                          // 测试获取产品ID为0的bug
r($bug->getProductBugsTest($productIdList[3], $typeList[0], $beginList[1], $endList[1])) && p() && e('0');                          // 测试获取产品ID为0的bug
r($bug->getProductBugsTest($productIdList[3], $typeList[1], $beginList[0], $endList[0])) && p() && e('0');                          // 测试获取产品ID为0解决日期为今天的bug
r($bug->getProductBugsTest($productIdList[3], $typeList[1], $beginList[1], $endList[1])) && p() && e('0');                          // 测试获取产品ID为0解决日期大于上周 小于下周的bug
r($bug->getProductBugsTest($productIdList[3], $typeList[2], $beginList[0], $endList[0])) && p() && e('0');                          // 测试获取产品ID为0创建日期为今天的bug
r($bug->getProductBugsTest($productIdList[3], $typeList[2], $beginList[1], $endList[1])) && p() && e('0');                          // 测试获取产品ID为0创建日期大于上周 小于下周的bug
