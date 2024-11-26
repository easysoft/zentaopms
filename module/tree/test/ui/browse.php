#!/usr/bin/env php
<?php

/**
title=维护产品模块
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/browse.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$module = zenData('module');
$module->gen(0, true);

$tester = new browseTester();
$tester->login();

r($tester->createModule('模块1')) && p('status,message') && e('SUCCESS,创建模块成功');
