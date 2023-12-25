#!/usr/bin/env php
<?php

/**

title=productModel->getstatsproducts();
cid=0

- 测试传入空的产品ID列表 @0
- 测试传入存在的产品ID列表第1条的name属性 @产品1
- 测试传入不存在的产品ID列表 @0
- 测试获取追加项目集信息空的产品ID列表 @0
- 测试获取追加项目集信息存在的产品ID列表
 - 第1条的programName属性 @项目集1
 - 第1条的programPM属性 @admin
- 测试获取追加项目集信息不存在的产品ID列表 @0
- 测试传入空的产品ID列表且按照项目集排序 @0
- 测试传入存在的产品ID列表且按照项目集排序第1条的name属性 @产品1
- 测试传入不存在的产品ID列表且按照项目集排序 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(5);
zdTable('product')->config('product')->gen(10);
$program = zdTable('project')->config('program');
$program->PM->range('admin');
$program->gen(10);

su('admin');

$productIdList[0] = array();
$productIdList[1] = range(1, 10);
$productIdList[2] = range(11, 20);
$appendProgram    = array(false, true);
$orderByList      = array('id_desc', 'program_asc');

global $tester;
$tester->loadModel('product');
r($tester->product->getStatsProducts($productIdList[0], $appendProgram[0], $orderByList[0])) && p()                          && e('0');             // 测试传入空的产品ID列表
r($tester->product->getStatsProducts($productIdList[1], $appendProgram[0], $orderByList[0])) && p('1:name')                  && e('产品1');         // 测试传入存在的产品ID列表
r($tester->product->getStatsProducts($productIdList[2], $appendProgram[0], $orderByList[0])) && p()                          && e('0');             // 测试传入不存在的产品ID列表
r($tester->product->getStatsProducts($productIdList[0], $appendProgram[1], $orderByList[0])) && p()                          && e('0');             // 测试获取追加项目集信息空的产品ID列表
r($tester->product->getStatsProducts($productIdList[1], $appendProgram[1], $orderByList[0])) && p('1:programName,programPM') && e('项目集1,admin'); // 测试获取追加项目集信息存在的产品ID列表
r($tester->product->getStatsProducts($productIdList[2], $appendProgram[1], $orderByList[0])) && p()                          && e('0');             // 测试获取追加项目集信息不存在的产品ID列表
r($tester->product->getStatsProducts($productIdList[0], $appendProgram[0], $orderByList[1])) && p()                          && e('0');             // 测试传入空的产品ID列表且按照项目集排序
r($tester->product->getStatsProducts($productIdList[1], $appendProgram[0], $orderByList[1])) && p('1:name')                  && e('产品1');         // 测试传入存在的产品ID列表且按照项目集排序
r($tester->product->getStatsProducts($productIdList[2], $appendProgram[0], $orderByList[1])) && p()                          && e('0');             // 测试传入不存在的产品ID列表且按照项目集排序
