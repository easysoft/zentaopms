#!/usr/bin/env php
<?php

/**

title=productTao->getExecutionList();
cid=0

- 测试项目跟产品的ID列表都为空的情况 @0
- 测试项目ID列表不为空，产品ID列表为空的情况 @0
- 测试项目ID列表不存在，产品ID列表为空的情况 @0
- 测试项目ID列表为空，产品ID列表不为空的情况 @0
- 测试项目ID列表不为空，产品ID列表不为空的情况
 - 第103条的productID属性 @7
 - 第103条的project属性 @11
- 测试项目ID列表不存在，产品ID列表不为空的情况 @0
- 测试项目ID列表为空，产品ID列表不存在的情况 @0
- 测试项目ID列表不为空，产品ID列表不存在的情况 @0
- 测试项目ID列表不存在，产品ID列表不存在的情况 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('project')->config('program')->gen(30);
zdTable('projectproduct')->config('projectproduct')->gen(30);
zdTable('user')->gen(5);
su('admin');

$projectIdList[0] = array();
$projectIdList[1] = array(11, 60 ,100);
$projectIdList[2] = range(20, 25);

$productIdList[0] = array();
$productIdList[1] = range(1, 10);
$productIdList[2] = range(100, 110);

global $tester;
$tester->loadModel('product');
r($tester->product->getExecutionList($projectIdList[0], $productIdList[0]))     && p()                        && e('0');    // 测试项目跟产品的ID列表都为空的情况
r($tester->product->getExecutionList($projectIdList[1], $productIdList[0]))     && p()                        && e('0');    // 测试项目ID列表不为空，产品ID列表为空的情况
r($tester->product->getExecutionList($projectIdList[2], $productIdList[0]))     && p()                        && e('0');    // 测试项目ID列表不存在，产品ID列表为空的情况
r($tester->product->getExecutionList($projectIdList[0], $productIdList[1]))     && p()                        && e('0');    // 测试项目ID列表为空，产品ID列表不为空的情况
r($tester->product->getExecutionList($projectIdList[1], $productIdList[1])[11]) && p('103:productID,project') && e('7,11'); // 测试项目ID列表不为空，产品ID列表不为空的情况
r($tester->product->getExecutionList($projectIdList[2], $productIdList[1]))     && p()                        && e('0');    // 测试项目ID列表不存在，产品ID列表不为空的情况
r($tester->product->getExecutionList($projectIdList[0], $productIdList[2]))     && p()                        && e('0');    // 测试项目ID列表为空，产品ID列表不存在的情况
r($tester->product->getExecutionList($projectIdList[1], $productIdList[2]))     && p()                        && e('0');    // 测试项目ID列表不为空，产品ID列表不存在的情况
r($tester->product->getExecutionList($projectIdList[2], $productIdList[2]))     && p()                        && e('0');    // 测试项目ID列表不存在，产品ID列表不存在的情况
