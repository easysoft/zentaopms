#!/usr/bin/env php
<?php

/**
title=关闭/激活分支
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
r($tester->closeBranch($productID))    && p('message,status') && e('关闭分支成功,SUCCESS');//关闭分支
r($tester->activateBranch($productID)) && p('message,status') && e('激活分支成功,SUCCESS');//激活分支

$tester->closeBrowser();
