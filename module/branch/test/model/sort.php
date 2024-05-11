#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

zenData('user')->gen(5);
zenData('branch')->gen(100);
su('admin');

/**

title=测试 branchModel->sort();
timeout=0
cid=1

- 测试产品 41 排序 @2,1

- 测试产品 42 排序 @4,3

- 测试产品 43 排序 @6,5

- 测试产品 44 排序 @8,7

*/
$branches = array(
    '41' => array('2' => '1', '1' => '2'),
    '42' => array('4' => '1', '3' => '2'),
    '43' => array('6' => '1', '5' => '2'),
    '44' => array('8' => '1', '7' => '2')
);

$branch = new branchTest();
r($branch->sortTest($branches['41']))         && p() && e('2,1'); // 测试产品 41 排序
r($branch->sortTest($branches['42']))         && p() && e('4,3'); // 测试产品 42 排序
r($branch->sortTest($branches['43']))         && p() && e('6,5'); // 测试产品 43 排序
r($branch->sortTest($branches['44']))         && p() && e('8,7'); // 测试产品 44 排序
