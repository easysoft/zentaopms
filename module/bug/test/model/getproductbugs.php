#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('bug')->gen(40);

/**

title=bugModel->getProductBugs();
cid=1
pid=1

*/

$productIdList = array('1,2,3', '4,5,6', '7,8,9', '0');
$typeList      = array('', 'resolved', 'opened');
$beginList     = array('today', 'lastweek');
$endList       = array('today', 'nextweek');

$bug = new bugTest();

r($bug->getProductBugsTest($productIdList[0], $typeList[0], $beginList[0], $endList[0])) && p() && e('1,2,3,4,5,6,7,8,9'); // 测试获取产品 1 2 3 类型 空 开始日期 今天 结束日期 今天 的bug
r($bug->getProductBugsTest($productIdList[0], $typeList[0], $beginList[1], $endList[1])) && p() && e('1,2,3,4,5,6,7,8,9'); // 测试获取产品 1 2 3 类型 空 开始日期 上周 结束日期 下周 的bug
r($bug->getProductBugsTest($productIdList[0], $typeList[1], $beginList[0], $endList[0])) && p() && e('8');                 // 测试获取产品 1 2 3 类型 resolved 开始日期 今天 结束日期 今天 的bug
r($bug->getProductBugsTest($productIdList[0], $typeList[1], $beginList[1], $endList[1])) && p() && e('1,2,3,4,5,6,7,8,9'); // 测试获取产品 1 2 3 类型 resolved 开始日期 上周 结束日期 下周 的bug
r($bug->getProductBugsTest($productIdList[0], $typeList[2], $beginList[0], $endList[0])) && p() && e('0');                 // 测试获取产品 1 2 3 类型 opened 开始日期 今天 结束日期 今天 的bug
r($bug->getProductBugsTest($productIdList[0], $typeList[2], $beginList[1], $endList[1])) && p() && e('0');                 // 测试获取产品 1 2 3 类型 opened 开始日期 上周 结束日期 下周 的bug

r($bug->getProductBugsTest($productIdList[1], $typeList[0], $beginList[0], $endList[0])) && p() && e('10,11,12,13,14,15,16,17,18'); // 测试获取产品 4 5 6 类型 空 开始日期 今天 结束日期 今天 的bug
r($bug->getProductBugsTest($productIdList[1], $typeList[0], $beginList[1], $endList[1])) && p() && e('10,11,12,13,14,15,16,17,18'); // 测试获取产品 4 5 6 类型 空 开始日期 上周 结束日期 下周 的bug
r($bug->getProductBugsTest($productIdList[1], $typeList[1], $beginList[0], $endList[0])) && p() && e('0');                          // 测试获取产品 4 5 6 类型 resolved 开始日期 今天 结束日期 今天 的bug
r($bug->getProductBugsTest($productIdList[1], $typeList[1], $beginList[1], $endList[1])) && p() && e('10,11,12,13,14,15');          // 测试获取产品 4 5 6 类型 resolved 开始日期 上周 结束日期 下周 的bug
r($bug->getProductBugsTest($productIdList[1], $typeList[2], $beginList[0], $endList[0])) && p() && e('0');                          // 测试获取产品 4 5 6 类型 opened 开始日期 今天 结束日期 今天 的bug
r($bug->getProductBugsTest($productIdList[1], $typeList[2], $beginList[1], $endList[1])) && p() && e('0');                          // 测试获取产品 4 5 6 类型 opened 开始日期 上周 结束日期 下周 的bug

r($bug->getProductBugsTest($productIdList[2], $typeList[0], $beginList[0], $endList[0])) && p() && e('19,20,21,22,23,24,25,26,27'); // 测试获取产品 7 8 9 类型 空 开始日期 今天 结束日期 今天 的bug
r($bug->getProductBugsTest($productIdList[2], $typeList[0], $beginList[1], $endList[1])) && p() && e('19,20,21,22,23,24,25,26,27'); // 测试获取产品 7 8 9 类型 空 开始日期 上周 结束日期 下周 的bug
r($bug->getProductBugsTest($productIdList[2], $typeList[1], $beginList[0], $endList[0])) && p() && e('0');                          // 测试获取产品 7 8 9 类型 resolved 开始日期 今天 结束日期 今天 的bug
r($bug->getProductBugsTest($productIdList[2], $typeList[1], $beginList[1], $endList[1])) && p() && e('22,23,24,25,26,27');          // 测试获取产品 7 8 9 类型 resolved 开始日期 上周 结束日期 下周 的bug
r($bug->getProductBugsTest($productIdList[2], $typeList[2], $beginList[0], $endList[0])) && p() && e('0');                          // 测试获取产品 7 8 9 类型 opened 开始日期 今天 结束日期 今天 的bug
r($bug->getProductBugsTest($productIdList[2], $typeList[2], $beginList[1], $endList[1])) && p() && e('24,25,26,27');                 // 测试获取产品 7 8 9 类型 opened 开始日期 上周 结束日期 下周 的bug

r($bug->getProductBugsTest($productIdList[3], $typeList[0], $beginList[0], $endList[0])) && p() && e('0'); // 测试获取产品 空 类型 空 开始日期 今天 结束日期 今天 的bug
r($bug->getProductBugsTest($productIdList[3], $typeList[0], $beginList[1], $endList[1])) && p() && e('0'); // 测试获取产品 空 类型 空 开始日期 上周 结束日期 下周 的bug
r($bug->getProductBugsTest($productIdList[3], $typeList[1], $beginList[0], $endList[0])) && p() && e('0'); // 测试获取产品 空 类型 resolved 开始日期 今天 结束日期 今天 的bug
r($bug->getProductBugsTest($productIdList[3], $typeList[1], $beginList[1], $endList[1])) && p() && e('0'); // 测试获取产品 空 类型 resolved 开始日期 上周 结束日期 下周 的bug
r($bug->getProductBugsTest($productIdList[3], $typeList[2], $beginList[0], $endList[0])) && p() && e('0'); // 测试获取产品 空 类型 opened 开始日期 今天 结束日期 今天 的bug
r($bug->getProductBugsTest($productIdList[3], $typeList[2], $beginList[1], $endList[1])) && p() && e('0'); // 测试获取产品 空 类型 opened 开始日期 上周 结束日期 下周 的bug
