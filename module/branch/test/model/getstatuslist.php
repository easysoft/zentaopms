#!/usr/bin/env php
<?php

/**

title=测试 branchModel::getStatusList();
timeout=0
cid=15331

- 执行branchTest模块的getStatusListTest方法，参数是101 属性1 @active
- 执行branchTest模块的getStatusListTest方法，参数是999  @0
- 执行branchTest模块的getStatusListTest方法  @0
- 执行branchTest模块的getStatusListTest方法，参数是102 属性3 @active
- 执行branchTest模块的getStatusListTest方法，参数是103 属性6 @closed

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$branch = zenData('branch');
$branch->id->range('1-10');
$branch->product->range('101{2},102{3},103{2},104{1}');
$branch->name->range('主分支,开发分支,测试分支,发布分支,hotfix分支');
$branch->status->range('active{5},closed{5}');
$branch->deleted->range('0');
$branch->gen(10);

zenData('user')->gen(5);
su('admin');

$branchTest = new branchModelTest();

r($branchTest->getStatusListTest(101)) && p('1') && e('active');
r($branchTest->getStatusListTest(999)) && p() && e('0');
r($branchTest->getStatusListTest(0)) && p() && e('0');
r($branchTest->getStatusListTest(102)) && p('3') && e('active');
r($branchTest->getStatusListTest(103)) && p('6') && e('closed');