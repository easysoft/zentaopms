#!/usr/bin/env php
<?php

/**
title=切换分支tab
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/manage.ui.class.php';

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

$tester = new manageTester();
$tester->login();

$productID['productID'] = 11;
$tabName = 'all';
$tabNum  = '3';
r($tester->switchTab($productID, $tabName, $tabNum)) && p('message,status') && e('切换至allTab成功,SUCCESS');//切换至全部tab

$tabName = 'active';
$tabNum  = '2';
r($tester->switchTab($productID, $tabName, $tabNum)) && p('message,status') && e('切换至activeTab成功,SUCCESS');//切换至激活tab

$tabName = 'closed';
$tabNum  = '1';
r($tester->switchTab($productID, $tabName, $tabNum)) && p('message,status') && e('切换至closedTab成功,SUCCESS');//切换至已关闭tab

$tester->closeBrowser();
