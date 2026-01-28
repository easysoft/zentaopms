#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->loadYaml('product')->gen(10);
zenData('user')->gen(5);
su('admin');

$branch = zenData('branch')->loadYaml('branch');
$branch->desc->range('分支描述');
$branch->gen(10);

/**

title=测试 branchModel->getByID();
timeout=0
cid=15324

*/
$branchIdList  = array(0, 1, 11);
$productIdList = range(1, 6);
$fieldList     = array('', 'name', 'desc');

global $tester;
$tester->loadModel('branch');
r($tester->branch->getByID($branchIdList[0], $productIdList[0], $fieldList[0])) && p()       && e('主干');     // 测试获取正常产品1下的主干分支信息
r($tester->branch->getByID($branchIdList[1], $productIdList[0], $fieldList[0])) && p('name') && e('分支1');    // 测试获取正常产品1下的分支1信息
r($tester->branch->getByID($branchIdList[2], $productIdList[0], $fieldList[0])) && p()       && e('0');        // 测试获取正常产品1下的不存在的分支信息
r($tester->branch->getByID($branchIdList[0], $productIdList[1], $fieldList[0])) && p()       && e('主干');     // 测试获取多分支产品2下的主干分支信息
r($tester->branch->getByID($branchIdList[1], $productIdList[1], $fieldList[0])) && p('name') && e('分支1');    // 测试获取多分支产品2下的分支1信息
r($tester->branch->getByID($branchIdList[2], $productIdList[1], $fieldList[0])) && p()       && e('0');        // 测试获取多分支产品2下的不存在的分支信息
r($tester->branch->getByID($branchIdList[0], $productIdList[0], $fieldList[1])) && p()       && e('主干');     // 测试获取正常产品1下的主干分支名称
r($tester->branch->getByID($branchIdList[1], $productIdList[0], $fieldList[1])) && p()       && e('分支1');    // 测试获取正常产品1下的分支1信息
r($tester->branch->getByID($branchIdList[2], $productIdList[0], $fieldList[1])) && p()       && e('0');        // 测试获取正常产品1下的不存在的分支名称
r($tester->branch->getByID($branchIdList[0], $productIdList[1], $fieldList[1])) && p()       && e('主干');     // 测试获取多分支产品2下的主干分支名称
r($tester->branch->getByID($branchIdList[1], $productIdList[1], $fieldList[1])) && p()       && e('分支1');    // 测试获取多分支产品2下的分支1信息
r($tester->branch->getByID($branchIdList[2], $productIdList[1], $fieldList[1])) && p()       && e('0');        // 测试获取多分支产品2下的不存在的分支名称
r($tester->branch->getByID($branchIdList[0], $productIdList[0], $fieldList[2])) && p()       && e('主干');     // 测试获取正常产品1下的主干分支描述
r($tester->branch->getByID($branchIdList[1], $productIdList[0], $fieldList[2])) && p()       && e('分支描述'); // 测试获取正常产品1下的分支1信息
r($tester->branch->getByID($branchIdList[2], $productIdList[0], $fieldList[2])) && p()       && e('0');        // 测试获取正常产品1下的不存在的分支描述
r($tester->branch->getByID($branchIdList[0], $productIdList[1], $fieldList[2])) && p()       && e('主干');     // 测试获取多分支产品2下的主干分支描述
r($tester->branch->getByID($branchIdList[1], $productIdList[1], $fieldList[2])) && p()       && e('分支描述'); // 测试获取多分支产品2下的分支1信息
r($tester->branch->getByID($branchIdList[2], $productIdList[1], $fieldList[2])) && p()       && e('0');        // 测试获取多分支产品2下的不存在的分支描述
