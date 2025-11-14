#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

// zendata数据准备
zenData('product')->gen(10);
zenData('branch')->gen(20);

su('admin');

/**

title=测试 branchModel::manage();
timeout=0
cid=15334

- 执行branchTest模块的manageTest方法，参数是4, array  @2
- 执行branchTest模块的manageTest方法，参数是1, array  @0
- 执行branchTest模块的manageTest方法，参数是2, array  @1
- 执行branchTest模块的manageTest方法，参数是3, array  @0
- 执行branchTest模块的manageTest方法，参数是5, array  @1
- 执行branchTest模块的manageTest方法，参数是6, array  @error

*/

$branchTest = new branchTest();

r($branchTest->manageTest(4, array(), array('新分支1', '新分支2'))) && p() && e('2');
r($branchTest->manageTest(1, array(1 => '更新的分支名'), array())) && p() && e('0');
r($branchTest->manageTest(2, array(3 => '分支3更新'), array('混合新分支1'))) && p() && e('1');
r($branchTest->manageTest(3, array(5 => '分支5更新'), array())) && p() && e('0');
r($branchTest->manageTest(5, array(), array('', '有效新分支'))) && p() && e('1');
r($branchTest->manageTest(6, array(11 => ''), array())) && p() && e('error');