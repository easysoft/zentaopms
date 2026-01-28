#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// zendata数据准备
zenData('product')->gen(10);
zenData('branch')->gen(20);

su('admin');

/**

title=测试 branchModel::manage();
timeout=0
cid=15334

- 测试添加2个新分支 @2
- 测试更新现有分支名称不添加新分支 @0
- 测试同时更新分支并添加1个新分支 @1
- 测试更新现有分支名称不添加新分支 @0
- 测试新分支列表包含空值过滤后添加1个 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->gen(10);
zenData('branch')->gen(20);
su('admin');

$branchTest = new branchModelTest();

r($branchTest->manageTest(4, array(), array('新分支1', '新分支2'))) && p() && e('2'); // 测试添加2个新分支
r($branchTest->manageTest(1, array(1 => '更新的分支名'), array())) && p() && e('0'); // 测试更新现有分支名称不添加新分支
r($branchTest->manageTest(2, array(3 => '分支3更新'), array('混合新分支1'))) && p() && e('1'); // 测试同时更新分支并添加1个新分支
r($branchTest->manageTest(3, array(5 => '分支5更新'), array())) && p() && e('0'); // 测试更新现有分支名称不添加新分支
r($branchTest->manageTest(5, array(), array('', '有效新分支'))) && p() && e('1'); // 测试新分支列表包含空值过滤后添加1个