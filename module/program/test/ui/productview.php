#!/usr/bin/env php
<?php

/**
title=产品视角下添加产品测试
timeout=0

- 添加产品，选择所属项目集后保存
 - 测试结果 @产品视角下创建产品成功
 - 最终测试状态 @SUCCESS
*/
chdir(__DIR__);
include '../lib/ui/productview.ui.class.php';

$tester = new createProgramTester();
$tester->login();

$programs = new stdClass();
$programs->program = '项目集3';

$products = new stdClass();
$products->programProduct = '产品A';

$productLines = new stdClass();
$productLines->productLine = '产品线A';

r($tester->createProgramProduct($programs, $products))  && p('message,status') && e('产品视角下创建产品成功，SUCCESS');     //产品视角下创建产品成功
r($tester->manageProductLine($programs, $productLines)) && p('message,status') && e('产品视角下维护产品线成功，SUCCESS');   //产品视角下创建产品成功