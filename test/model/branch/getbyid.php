#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->getById();
cid=1
pid=1

测试获取 branchID 1 的分支信息 >> 分支1
测试获取 branchID 1 productID 41 的分支信息 >> 分支1
测试获取 branchID 1 productID 41 field 空 的分支信息 >> 分支1
测试获取 branchID 2 的分支信息 >> 分支2
测试获取 branchID 2 productID 41 的分支信息 >> 分支2
测试获取 branchID 2 productID 41 field 空 的分支信息 >> 分支2
测试获取 branchID 3 的分支信息 >> 分支3
测试获取 branchID 3 productID 41 的分支信息 >> 分支3
测试获取 branchID 3 productID 41 field 空 的分支信息 >> 分支3
测试获取 branchID 空 的分支信息 >> 0
测试获取 branchID 空 productID 41 的分支信息 >> 主干
测试获取 branchID 空 productID 81 的分支信息 >> 主干
测试获取 branchID 空 productID 1 的分支信息 >> 主干
测试获取 branchID 空 productID 41 field 空 的分支信息 >> 主干
测试获取 branchID 空 productID 81 field 空 的分支信息 >> 主干
测试获取 branchID 空 productID 1  field 空 的分支信息 >> 主干

*/
$branchID  = array(1, 2, 3, '');
$productID = array(41, 81, 1);
$field     = '';

$branch = new branchTest();

r($branch->getByIdTest($branchID[0]))                        && p()       && e('分支1'); // 测试获取 branchID 1 的分支信息
r($branch->getByIdTest($branchID[0], $productID[0]))         && p()       && e('分支1'); // 测试获取 branchID 1 productID 41 的分支信息
r($branch->getByIdTest($branchID[0], $productID[0], $field)) && p('name') && e('分支1'); // 测试获取 branchID 1 productID 41 field 空 的分支信息
r($branch->getByIdTest($branchID[1]))                        && p()       && e('分支2'); // 测试获取 branchID 2 的分支信息
r($branch->getByIdTest($branchID[1], $productID[0]))         && p()       && e('分支2'); // 测试获取 branchID 2 productID 41 的分支信息
r($branch->getByIdTest($branchID[1], $productID[0], $field)) && p('name') && e('分支2'); // 测试获取 branchID 2 productID 41 field 空 的分支信息
r($branch->getByIdTest($branchID[2]))                        && p()       && e('分支3'); // 测试获取 branchID 3 的分支信息
r($branch->getByIdTest($branchID[2], $productID[0]))         && p()       && e('分支3'); // 测试获取 branchID 3 productID 41 的分支信息
r($branch->getByIdTest($branchID[2], $productID[0], $field)) && p('name') && e('分支3'); // 测试获取 branchID 3 productID 41 field 空 的分支信息
r($branch->getByIdTest($branchID[3]))                        && p()       && e('0');     // 测试获取 branchID 空 的分支信息
r($branch->getByIdTest($branchID[3], $productID[0]))         && p()       && e('主干');  // 测试获取 branchID 空 productID 41 的分支信息
r($branch->getByIdTest($branchID[3], $productID[1]))         && p()       && e('主干');  // 测试获取 branchID 空 productID 81 的分支信息
r($branch->getByIdTest($branchID[3], $productID[2]))         && p()       && e('主干');  // 测试获取 branchID 空 productID 1 的分支信息
r($branch->getByIdTest($branchID[3], $productID[0], $field)) && p('name') && e('主干');  // 测试获取 branchID 空 productID 41 field 空 的分支信息
r($branch->getByIdTest($branchID[3], $productID[1], $field)) && p('name') && e('主干');  // 测试获取 branchID 空 productID 81 field 空 的分支信息
r($branch->getByIdTest($branchID[3], $productID[2], $field)) && p('name') && e('主干');  // 测试获取 branchID 空 productID 1  field 空 的分支信息