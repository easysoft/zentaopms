#!/usr/bin/env php
<?php

/**

title=创建文档库测试
timeout=0
cid=0

- 库名称为空，创建失败
 - 测试结果 @库名称非空校验成功
 - 最终测试状态 @SUCCESS
- 创建我的文档库成功
 - 测试结果 @创建我的文档库成功
 - 最终测试状态 @SUCCESS

*/

chdir(__DIR__);
include '../lib/createdoclib.ui.class.php';

$tester = new createDocTester();
$tester->login();

$libName = array();
$libName['null']     = '';
$libName['myDocLib'] = '我的文档库1';

r($tester->createDocLib($libName['null']))     && p('message,status') && e('库名称非空校验成功,SUCCESS'); //库名称为空，创建失败
r($tester->createDocLib($libName['myDocLib'])) && p('message,status') && e('创建我的文档库成功,SUCCESS'); //创建我的文档库成功