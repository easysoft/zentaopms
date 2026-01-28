#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testreport')->gen(30);
zenData('user')->gen(1);

su('admin');

/**

title=测试 testreportModel->getPairs();
cid=19119
pid=1

- 测试查询产品 0 appendID 0 的键对 @30,29,28,27,26,25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1
- 测试查询产品 0 appendID 1 的键对 @30,29,28,27,26,25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1
- 测试查询产品 0 appendID 101 的键对 @30,29,28,27,26,25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1
- 测试查询产品 1 appendID 0 的键对 @21,11,1
- 测试查询产品 1 appendID 1 的键对 @21,11,1
- 测试查询产品 1 appendID 101 的键对 @21,11,1
- 测试查询产品 2 appendID 0 的键对 @22,12,2
- 测试查询产品 2 appendID 1 的键对 @22,12,2,1
- 测试查询产品 2 appendID 101 的键对 @22,12,2
- 测试查询产品 3 appendID 0 的键对 @23,13,3
- 测试查询产品 3 appendID 1 的键对 @23,13,3,1
- 测试查询产品 3 appendID 101 的键对 @23,13,3

*/

$productIdList = array(0, 1, 2, 3);
$appendIdList  = array(0, 1, 101);

$testreport = new testreportModelTest();

r($testreport->getPairsTest($productIdList[0], $appendIdList[0])) && p() && e('30,29,28,27,26,25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1'); // 测试查询产品 0 appendID 0 的键对
r($testreport->getPairsTest($productIdList[0], $appendIdList[1])) && p() && e('30,29,28,27,26,25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1'); // 测试查询产品 0 appendID 1 的键对
r($testreport->getPairsTest($productIdList[0], $appendIdList[2])) && p() && e('30,29,28,27,26,25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1'); // 测试查询产品 0 appendID 101 的键对
r($testreport->getPairsTest($productIdList[1], $appendIdList[0])) && p() && e('21,11,1');   // 测试查询产品 1 appendID 0 的键对
r($testreport->getPairsTest($productIdList[1], $appendIdList[1])) && p() && e('21,11,1');   // 测试查询产品 1 appendID 1 的键对
r($testreport->getPairsTest($productIdList[1], $appendIdList[2])) && p() && e('21,11,1');   // 测试查询产品 1 appendID 101 的键对
r($testreport->getPairsTest($productIdList[2], $appendIdList[0])) && p() && e('22,12,2');   // 测试查询产品 2 appendID 0 的键对
r($testreport->getPairsTest($productIdList[2], $appendIdList[1])) && p() && e('22,12,2,1'); // 测试查询产品 2 appendID 1 的键对
r($testreport->getPairsTest($productIdList[2], $appendIdList[2])) && p() && e('22,12,2');   // 测试查询产品 2 appendID 101 的键对
r($testreport->getPairsTest($productIdList[3], $appendIdList[0])) && p() && e('23,13,3');   // 测试查询产品 3 appendID 0 的键对
r($testreport->getPairsTest($productIdList[3], $appendIdList[1])) && p() && e('23,13,3,1'); // 测试查询产品 3 appendID 1 的键对
r($testreport->getPairsTest($productIdList[3], $appendIdList[2])) && p() && e('23,13,3');   // 测试查询产品 3 appendID 101 的键对
