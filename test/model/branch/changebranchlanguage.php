#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->changeBranchLanguage();
cid=1
pid=1

测试修改productID 1 的分支语言项 >> 新建%s
测试修改productID 41 的分支语言项 >> 新建分支
测试修改productID 81 的分支语言项 >> 新建平台
测试修改productID 42 的分支语言项 >> 新建分支

*/
$productID = array(1, 41, 81, 42);

$branch = new branchTest();

r($branch->changeBranchLanguageTest($productID[0])) && p() && e('新建%s');   // 测试修改productID 1 的分支语言项
r($branch->changeBranchLanguageTest($productID[1])) && p() && e('新建分支'); // 测试修改productID 41 的分支语言项
r($branch->changeBranchLanguageTest($productID[2])) && p() && e('新建平台'); // 测试修改productID 81 的分支语言项
r($branch->changeBranchLanguageTest($productID[3])) && p() && e('新建分支'); // 测试修改productID 42 的分支语言项