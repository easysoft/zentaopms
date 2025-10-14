#!/usr/bin/env php
<?php

/**

title=创建用例库
timeout=0
cid=73

- 用例库名称置空保存，检查提示信息
 - 测试结果 @创建用例库表单页必填提示信息正确
 - 最终测试状态 @SUCCESS
- 无用例库时创建用例库
 - 测试结果 @用例库创建成功
 - 最终测试状态 @SUCCESS
- 有用例库时创建用例库
 - 测试结果 @用例库创建成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/createcaselib.ui.class.php';

zenData('testsuite')->gen(0);

$tester = new createCaselibTester();
$tester->login();

//设置数据
$caselib = array(
    array('name' => ''),
    array('name' => '用例库1'),
    array('name' => '用例库2'),
);
$libID = array('', '1');

r($tester->createCaselib($caselib['0'], $libID['0'])) && p('message,status') && e('创建用例库表单页必填提示信息正确,SUCCESS'); // 用例库名称置空保存，检查提示信息
r($tester->createCaselib($caselib['1'], $libID['0'])) && p('message,status') && e('用例库创建成功,SUCCESS');                   // 无用例库时创建用例库
r($tester->createCaselib($caselib['2'], $libID['1'])) && p('message,status') && e('用例库创建成功,SUCCESS');                   // 有用例库时创建用例库

$tester->closeBrowser();
