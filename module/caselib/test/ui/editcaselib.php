#!/usr/bin/env php
<?php

/**

title=编辑用例库
timeout=0
cid=73

- 用例库名称置空保存，检查提示信息
 - 测试结果 @编辑用例库表单页必填提示信息正确
 - 最终测试状态 @SUCCESS
- 编辑用例库
 - 测试结果 @用例库编辑成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/editcaselib.ui.class.php';

$caselib = zenData('testsuite');
$caselib->id->range('1');
$caselib->project->range('0');
$caselib->product->range('0');
$caselib->name->range('用例库1');
$caselib->desc->range('用例库的描述');
$caselib->type->range('library');
$caselib->order->range('0');
$caselib->addedBy->range('admin');
$caselib->addedDate->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$caselib->deleted->range('0');
$caselib->gen(1);

$tester = new editCaselibTester();
$tester->login();

//设置数据
$caselib = array(
    array('name' => ''),
    array('name' => '用例库2'),
);

r($tester->editCaselib($caselib['0'])) && p('message,status') && e('编辑用例库表单页必填提示信息正确,SUCCESS'); // 用例库名称置空保存，检查提示信息
r($tester->editCaselib($caselib['1'])) && p('message,status') && e('用例库编辑成功,SUCCESS'); // 编辑用例库

$tester->closeBrowser();
