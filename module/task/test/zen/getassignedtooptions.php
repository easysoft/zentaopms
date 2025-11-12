#!/usr/bin/env php
<?php

/**

title=测试 taskZen::getAssignedToOptions();
timeout=0
cid=0

- 步骤1:空manageLink-multiple模式multiple为true第multiple条的multiple属性 @1
- 步骤2:空manageLink-multiple模式checkbox为true第multiple条的checkbox属性 @1
- 步骤3:空manageLink-multiple模式第一个工具栏按钮为selectAll第multiple条的firstToolbarKey属性 @selectAll
- 步骤4:空manageLink-multiple模式第二个工具栏按钮为cancelSelect第multiple条的secondToolbarKey属性 @cancelSelect
- 步骤5:带manageLink时single包含1个工具栏按钮第single条的toolbarCount属性 @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例(变量名与模块名一致)
$taskTest = new taskZenTest();

// 4. 执行测试步骤(必须至少5个)
r($taskTest->getAssignedToOptionsTest('')) && p('multiple:multiple') && e('1'); // 步骤1:空manageLink-multiple模式multiple为true
r($taskTest->getAssignedToOptionsTest('')) && p('multiple:checkbox') && e('1'); // 步骤2:空manageLink-multiple模式checkbox为true
r($taskTest->getAssignedToOptionsTest('')) && p('multiple:firstToolbarKey') && e('selectAll'); // 步骤3:空manageLink-multiple模式第一个工具栏按钮为selectAll
r($taskTest->getAssignedToOptionsTest('')) && p('multiple:secondToolbarKey') && e('cancelSelect'); // 步骤4:空manageLink-multiple模式第二个工具栏按钮为cancelSelect
r($taskTest->getAssignedToOptionsTest('http://test.com/manage')) && p('single:toolbarCount') && e('1'); // 步骤5:带manageLink时single包含1个工具栏按钮