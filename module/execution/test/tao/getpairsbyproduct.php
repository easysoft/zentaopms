#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('user')->gen(5);
su('admin');

zenData('project')->loadYaml('execution')->gen(30);
$projectproduct = zenData('projectproduct')->loadYaml('projectproduct');
$projectproduct->product->range('1-10');
$projectproduct->gen(30);

/**

title=测试executionModel->getPairsByProduct();
timeout=0
cid=1

*/

$productIdList = range(1, 5);

global $tester;
$tester->loadModel('execution');
r($tester->execution->getPairsByProduct(array()))               && p()      && e('0');     // 测试空数据
r($tester->execution->getPairsByProduct($productIdList))        && p('101') && e('迭代5'); // 获取关联产品1-5的执行
r(count($tester->execution->getPairsByProduct($productIdList))) && p()      && e('11');    // 获取关联产品1-5的执行的数量
