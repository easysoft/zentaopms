#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

zenData('product')->loadYaml('product')->gen(10);
zenData('branch')->loadYaml('branch')->gen(20);
zenData('project')->loadYaml('execution')->gen(20);
zenData('projectproduct')->loadYaml('projectproduct')->gen(20);
su('admin');

/**

title=测试 branchModel->getList();
timeout=0
cid=15326

- 执行branch模块的getListTest方法，参数是$productID[0]  @,0,1,2
- 执行branch模块的getListTest方法，参数是$productID[0], $executionID[0]  @0
- 执行branch模块的getListTest方法，参数是$productID[0], $executionID[0], $browseType[0]  @0
- 执行branch模块的getListTest方法，参数是$productID[0], $executionID[1], $browseType[0]  @,0,1
- 执行branch模块的getListTest方法，参数是$productID[0], $executionID[1], $browseType[1]  @0
- 执行branch模块的getListTest方法，参数是$productID[0], $executionID[1], $browseType[2]  @,0,1
- 执行branch模块的getListTest方法，参数是$productID[0], $executionID[1], $browseType[0], $mainBranch  @,1
- 执行branch模块的getListTest方法，参数是$productID[1]  @,0
- 执行branch模块的getListTest方法，参数是$productID[1], $executionID[1]  @0
- 执行branch模块的getListTest方法，参数是$productID[1], $executionID[1], $browseType[0]  @0
- 执行branch模块的getListTest方法，参数是$productID[1], $executionID[3], $browseType[0]  @,0
- 执行branch模块的getListTest方法，参数是$productID[1], $executionID[3], $browseType[1]  @0
- 执行branch模块的getListTest方法，参数是$productID[1], $executionID[3], $browseType[2]  @,0
- 执行branch模块的getListTest方法，参数是$productID[1], $executionID[3], $browseType[0], $mainBranch  @0
- 执行branch模块的getListTest方法，参数是$productID[2]  @0
- 执行branch模块的getListTest方法，参数是$productID[2], $executionID[2]  @0
- 执行branch模块的getListTest方法，参数是$productID[2], $executionID[2], $browseType[0]  @0
- 执行branch模块的getListTest方法，参数是$productID[2], $executionID[3], $browseType[0]  @0
- 执行branch模块的getListTest方法，参数是$productID[2], $executionID[3], $browseType[1]  @0
- 执行branch模块的getListTest方法，参数是$productID[2], $executionID[3], $browseType[2]  @0
- 执行branch模块的getListTest方法，参数是$productID[2], $executionID[3], $browseType[0], $mainBranch  @0

*/
$productID   = array(6, 5, 11);
$executionID = array(101, 102, 103, 0);
$browseType  = array('all', 'closed', 'active');
$mainBranch  = false;

$branch = new branchTest();

r($branch->getListTest($productID[0]))                                               && p('', '|') && e(',0,1,2');
r($branch->getListTest($productID[0], $executionID[0]))                              && p('', '|') && e('0');
r($branch->getListTest($productID[0], $executionID[0], $browseType[0]))              && p('', '|') && e('0');
r($branch->getListTest($productID[0], $executionID[1], $browseType[0]))              && p('', '|') && e(',0,1');
r($branch->getListTest($productID[0], $executionID[1], $browseType[1]))              && p('', '|') && e('0');
r($branch->getListTest($productID[0], $executionID[1], $browseType[2]))              && p('', '|') && e(',0,1');
r($branch->getListTest($productID[0], $executionID[1], $browseType[0], $mainBranch)) && p('', '|') && e(',1');

r($branch->getListTest($productID[1]))                                               && p('', '|') && e(',0');
r($branch->getListTest($productID[1], $executionID[1]))                              && p('', '|') && e('0');
r($branch->getListTest($productID[1], $executionID[1], $browseType[0]))              && p('', '|') && e('0');
r($branch->getListTest($productID[1], $executionID[3], $browseType[0]))              && p('', '|') && e(',0');
r($branch->getListTest($productID[1], $executionID[3], $browseType[1]))              && p('', '|') && e('0');
r($branch->getListTest($productID[1], $executionID[3], $browseType[2]))              && p('', '|') && e(',0');
r($branch->getListTest($productID[1], $executionID[3], $browseType[0], $mainBranch)) && p('', '|') && e('0');

r($branch->getListTest($productID[2]))                                               && p('', '|') && e('0');
r($branch->getListTest($productID[2], $executionID[2]))                              && p('', '|') && e('0');
r($branch->getListTest($productID[2], $executionID[2], $browseType[0]))              && p('', '|') && e('0');
r($branch->getListTest($productID[2], $executionID[3], $browseType[0]))              && p('', '|') && e('0');
r($branch->getListTest($productID[2], $executionID[3], $browseType[1]))              && p('', '|') && e('0');
r($branch->getListTest($productID[2], $executionID[3], $browseType[2]))              && p('', '|') && e('0');
r($branch->getListTest($productID[2], $executionID[3], $browseType[0], $mainBranch)) && p('', '|') && e('0');
