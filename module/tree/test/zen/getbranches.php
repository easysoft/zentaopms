#!/usr/bin/env php
<?php

/**

title=测试 treeZen::getBranches();
timeout=0
cid=19394

- 执行treeTest模块的getBranchesTest方法，参数是$normalProduct, 'story', 0  @0
- 执行treeTest模块的getBranchesTest方法，参数是$branchProduct, 'story', 0  @0
- 执行treeTest模块的getBranchesTest方法，参数是$platformProduct, 'bug', 0  @0
- 执行treeTest模块的getBranchesTest方法，参数是$branchProduct, 'task', 0  @0
- 执行treeTest模块的getBranchesTest方法，参数是$platformProduct, 'case', 0  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/treezen.unittest.class.php';

su('admin');

$treeTest = new treeTest();

// 测试用例1：普通产品（normal类型）在story视图类型下获取分支
$normalProduct = (object)array('id' => 1, 'type' => 'normal', 'name' => 'Normal Product');
r($treeTest->getBranchesTest($normalProduct, 'story', 0)) && p() && e('0');

// 测试用例2：多分支产品在story视图类型下获取分支
$branchProduct = (object)array('id' => 2, 'type' => 'branch', 'name' => 'Branch Product');
r($treeTest->getBranchesTest($branchProduct, 'story', 0)) && p() && e('0');

// 测试用例3：多分支产品在bug视图类型下获取分支
$platformProduct = (object)array('id' => 3, 'type' => 'platform', 'name' => 'Platform Product');
r($treeTest->getBranchesTest($platformProduct, 'bug', 0)) && p() && e('0');

// 测试用例4：非story/bug/case视图类型的产品获取分支
$branchProduct = (object)array('id' => 4, 'type' => 'branch', 'name' => 'Branch Product');
r($treeTest->getBranchesTest($branchProduct, 'task', 0)) && p() && e('0');

// 测试用例5：case视图类型的多分支产品获取分支
$platformProduct = (object)array('id' => 5, 'type' => 'platform', 'name' => 'Platform Product');
r($treeTest->getBranchesTest($platformProduct, 'case', 0)) && p() && e('0');