#!/usr/bin/env php
<?php

/**

title=切换产品列表tab
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/all.ui.class.php';

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->status->range('normal{2},closed{1}');
$product->type->range('normal');
$product->gen(3);

$tester = new allTester();
$tester->login();

r($tester->switchTab('all', '3'))    && p('message,status') && e('切换至allTab成功,SUCCESS');//切换至全部产品Tab
r($tester->switchTab('open', '2'))   && p('message,status') && e('切换至openTab成功,SUCCESS');//切换至未关闭Tab
r($tester->switchTab('closed', '1')) && p('message,status') && e('切换至closedTab成功,SUCCESS');//切换至结束Tab

$tester->closeBrowser();
