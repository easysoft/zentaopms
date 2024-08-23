#!/usr/bin/env php
<?php

/**

title=产品视角下添加产品测试
timeout=0

- 添加产品，选择所属项目集后保存
 - 测试结果 @产品保存成功且显示在项目集列表。
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/productview.ui.class.php';

$tester = new createProgramTester();
$tester->login();

r($tester->createProgramProduct()) && p('message,status') && e('产品视角下创建产品成功，SUCCESS');     //产品视角下创建产品成功
r($tester->manageProductLine())    && p('message,status') && e('产品视角下维护产品线成功，SUCCESS');   //产品视角下创建产品成功
