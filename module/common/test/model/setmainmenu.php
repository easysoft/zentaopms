#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('1-1');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$tester->loadModel('common');


$testObj = new commonModelTest();
/**

title=测试 commonModel::setMainMenu()
timeout=0
cid=0

- 步骤1：正常输入 @error:TypeError
- 步骤2：边界值输入 @error:TypeError
- 步骤3：无效输入 @error:TypeError
- 步骤4：大值输入 @error:TypeError
- 步骤5：业务规则验证 @error:TypeError
- 步骤6：DevOps首页菜单不包含代码问题二级导航 @0
- 步骤7：代码库页面代码问题菜单保留 @1
- 步骤8：人工审查问题子菜单保留 @1
- 步骤9：静态扫描问题子菜单保留 @1
- 步骤10：代码库列表页面使用DevOps首页菜单 @1

*/

r($testObj->setMainMenuTest()) && p() && e('error:TypeError'); // 步骤1：正常输入
r($testObj->setMainMenuTest()) && p() && e('error:TypeError'); // 步骤2：边界值输入
r($testObj->setMainMenuTest()) && p() && e('error:TypeError'); // 步骤3：无效输入
r($testObj->setMainMenuTest()) && p() && e('error:TypeError'); // 步骤4：大值输入
r($testObj->setMainMenuTest()) && p() && e('error:TypeError'); // 步骤5：业务规则验证

$devopsHomeMenu = $testObj->setMainMenuDevopsHomeMenuTest();

r($devopsHomeMenu) && p('issue') && e('0');           // 步骤6：DevOps首页菜单不包含代码问题二级导航
r($devopsHomeMenu) && p('review') && e('1');          // 步骤7：代码库页面代码问题菜单保留
r($devopsHomeMenu) && p('reviewSubMenu') && e('1');   // 步骤8：人工审查问题子菜单保留
r($devopsHomeMenu) && p('codescanSubMenu') && e('1'); // 步骤9：静态扫描问题子菜单保留
r($devopsHomeMenu) && p('homeMenuActive') && e('1');  // 步骤10：代码库列表页面使用DevOps首页菜单
