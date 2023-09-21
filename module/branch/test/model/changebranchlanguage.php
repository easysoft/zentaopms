#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/branch.class.php';

$product = zdTable('product');
$product->id->range('41-45');
$product->type->range('normal,branch,platform');
$product->gen(5);

zdTable('branch')->gen(10);
su('admin');

/**

title=测试 branchModel->changeBranchLanguage();
timeout=0
cid=1

*/
$productID = array(41, 42, 43, 81);

$branch = new branchTest();

r($branch->changeBranchLanguageTest($productID[0])) && p() && e('新建%s');   // 正常产品
r($branch->changeBranchLanguageTest($productID[1])) && p() && e('新建分支'); // 多分支产品
r($branch->changeBranchLanguageTest($productID[2])) && p() && e('新建平台'); // 多平台产品
r($branch->changeBranchLanguageTest($productID[3])) && p() && e('新建%s');   // 不存在的产品
