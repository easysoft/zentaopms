#!/usr/bin/env php
<?php

/**
title=批量编辑分支测试
timeout=0
cid=0

-校验分支名称必填
 -测试结果 @分支名称必填提示信息正确
 -最终测试状态 @SUCCESS
-校验分支重复
 -测试结果 @分支已存在提示信息正确
 -最终测试状态 @SUCCESS
-校验分支正常编辑
 -测试结果 @批量编辑分支成功
 -最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/batcheditbranch.ui.class.php';

$product = zenData('product');
$product->id->range('11');
$product->name->range('多分支产品');
$product->status->range('normal');
$product->type->range('branch');
$product->gen(1);

$branch = zenData('branch');
$branch->id->range('1-2');
$branch->product->range('11');
$branch->name->range('分支01,分支02');
$branch->desc->range('分支描述01,分支描述02');
$branch->gen(2);

$tester = new batchEditBranchTester();
$tester->login();

$productID['productID'] = 11;
$editBranch = new stdClass();
$editBranch->name = '';
r($tester->batchEditBranch($editBranch, $productID)) && p('message,status') && e('分支名称必填提示信息正确,SUCCESS');

$editBranch->name = '分支02';
r($tester->batchEditBranch($editBranch, $productID)) && p('message,status') && e('分支已存在提示信息正确,SUCCESS');

$editBranch->name = '分支test编辑';
$editBranch->desc = '这是一行分支的描述';
r($tester->batchEditBranch($editBranch, $productID)) && p('message,status') && e('批量编辑分支成功,SUCCESS');

$tester->closeBrowser();
