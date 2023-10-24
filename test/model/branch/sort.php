#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->sort();
cid=1
pid=1

测试产品 41 排序 >> 1,2
测试产品 42 排序 >> 3,4
测试产品 43 排序 >> 5,6
测试产品 44 排序 >> 7,8
测试产品 41 order 排序 >> 2,1
测试产品 42 order 排序 >> 4,3
测试产品 43 order 排序 >> 6,5
测试产品 44 order 排序 >> 8,7

*/
$branches = array('0,2,1', '0,4,3', '0,6,5', '0,8,7');
$order    = 'order';

$branch = new branchTest();

r($branch->sortTest($branches[0]))         && p() && e('1,2'); // 测试产品 41 排序
r($branch->sortTest($branches[1]))         && p() && e('3,4'); // 测试产品 42 排序
r($branch->sortTest($branches[2]))         && p() && e('5,6'); // 测试产品 43 排序
r($branch->sortTest($branches[3]))         && p() && e('7,8'); // 测试产品 44 排序
r($branch->sortTest($branches[0], $order)) && p() && e('2,1'); // 测试产品 41 order 排序
r($branch->sortTest($branches[1], $order)) && p() && e('4,3'); // 测试产品 42 order 排序
r($branch->sortTest($branches[2], $order)) && p() && e('6,5'); // 测试产品 43 order 排序
r($branch->sortTest($branches[3], $order)) && p() && e('8,7'); // 测试产品 44 order 排序
