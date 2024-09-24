#!/usr/bin/env php
<?php

/**
title=创建分支测试
timeout=0
cid=12

-校验分支名称必填
 -测试结果 @分支名称必填提示信息正确
 -最终测试状态 @SUCCESS
-校验分支正常创建
 -测试结果 @创建分支成功
 -最终测试状态 @SUCCESS
-校验分支重复
 -测试结果 @分支已存在提示信息正确
 -最终测试状态 @SUCCESS
*/
chdir(__DIR__);
include '../lib/createbranch.ui.class.php';

$product = zenData('product');
$product->id->range('11');
$product->name->range('多分支产品');
$product->status->range('normal');
$product->type->range('branch');
$product->gen(1);
zendata('branch')->loadYaml('branch', false, 2)->gen(0);
$tester = new createBranchTester();
$tester->login();

$productID['productID'] = 11;
$branch = new stdClass();
$branch->name = '';
r($tester->createBranch($branch, $productID)) && p('message,status') && e('分支名称必填提示信息正确,SUCCESS');

$branch->name = '分支test';
$branch->desc = '这是一行分支的描述';
r($tester->createBranch($branch, $productID)) && p('message,status') && e('创建分支成功,SUCCESS');

r($tester->createBranch($branch, $productID)) && p('message,status') && e('分支已存在提示信息正确,SUCCESS');

$tester->closeBrowser();
