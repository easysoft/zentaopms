#!/usr/bin/env php
<?php

/**

title=测试 branchModel::sort();
timeout=0
cid=15338

- 执行branchTest模块的sortTest方法，参数是array  @2,1

- 执行branchTest模块的sortTest方法，参数是array  @4,3,6,5

- 执行branchTest模块的sortTest方法，参数是array  @7
- 执行branchTest模块的sortTest方法，参数是array  @0
- 执行branchTest模块的sortTest方法，参数是array  @8,9,10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

zenData('user')->gen(5);
$branch_table = zenData('branch');
$branch_table->id->range('1-20');
$branch_table->product->range('1-5');
$branch_table->name->range('分支1,分支2,分支3,分支4,分支5{4}');
$branch_table->order->range('1-20');
$branch_table->status->range('active{15},closed{5}');
$branch_table->deleted->range('0{18},1{2}');
$branch_table->gen(20);

su('admin');

$branchTest = new branchTest();

r($branchTest->sortTest(array('2' => '1', '1' => '2'))) && p() && e('2,1');
r($branchTest->sortTest(array('4' => '1', '3' => '2', '6' => '3', '5' => '4'))) && p() && e('4,3,6,5');
r($branchTest->sortTest(array('7' => '1'))) && p() && e('7');
r($branchTest->sortTest(array())) && p() && e('0');
r($branchTest->sortTest(array('8' => '5', '9' => '5', '10' => '5'))) && p() && e('8,9,10');