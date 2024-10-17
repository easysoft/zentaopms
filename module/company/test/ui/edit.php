#!/usr/bin/env php
<?php

/**

title=编辑修改公司信息
timeout=0
cid=2

- 编辑修改公司信息
 - 测试结果 @编辑公司信息成功
 - 最终测试状态 @SUCCESS
-重置匿名登录为不允许
 - 测试结果 @匿名登录修改为不允许
 - 最终测试状态 @SUCCESS

*/

chdir(__DIR__);
include '../lib/editcompany.ui.class.php';

$tester = new editCompanyTester();
$tester->login();

$company = new stdClass();
$company->name    = '禅道软件';
$company->phone   = '0532-86893032';
$company->address = '青铁广场18楼';
$company->zipcode = '266520';

r($tester->editCompany($company)) && p('message') && e('编辑公司信息成功');       //编辑修改公司信息
r($tester->initPrimary($company)) && p('message') && e('匿名登录修改为不允许');   //重置匿名登录为不允许

$tester->closeBrowser();
