#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('projectproduct')->loadYaml('projectproduct')->gen(20);
su('admin');

/**

title=测试 branchModel->getIdListByRelation();
timeout=0
cid=15342

*/

global $tester;
$branchModel = $tester->loadModel('branch');

r($branchModel->getIdListByRelation(0, 0))   && p() && e('0');           // 空产品，空执行
r($branchModel->getIdListByRelation(1, 0))   && p() && e('0');           // 正常的产品，空执行
r($branchModel->getIdListByRelation(1, 1))   && p() && e('0');           // 正常的产品，错误的执行
r($branchModel->getIdListByRelation(1, 11))  && p('0:branch') && e('0'); // 正常的产品，正确的执行
r($branchModel->getIdListByRelation(6, 102)) && p('1:branch') && e('1'); // 多分支产品，正确的执行
