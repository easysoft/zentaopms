#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::setMenuForCaseEdit();
timeout=0
cid=0

- 步骤1：project标签页正常情况
 - 属性tab @project
 - 属性projectID @1
- 步骤2：execution标签页正常情况
 - 属性tab @execution
 - 属性executionID @3
- 步骤3：qa标签页正常情况属性tab @qa
- 步骤4：execution标签页但executionID为0时使用case的execution
 - 属性tab @execution
 - 属性executionID @2
- 步骤5：无效标签页情况
 - 属性tab @other
 - 属性projectID @~~
 - 属性executionID @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. 不需要数据库数据准备（该方法只是设置菜单，不查询数据库）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建测试用例对象
$case = new stdclass();
$case->id = 1;
$case->project = 1;
$case->product = 1;
$case->execution = 2;
$case->branch = 1;

r($testcaseTest->setMenuForCaseEditTest($case, 0, 'project')) && p('tab,projectID') && e('project,1'); // 步骤1：project标签页正常情况
r($testcaseTest->setMenuForCaseEditTest($case, 3, 'execution')) && p('tab,executionID') && e('execution,3'); // 步骤2：execution标签页正常情况
r($testcaseTest->setMenuForCaseEditTest($case, 0, 'qa')) && p('tab') && e('qa'); // 步骤3：qa标签页正常情况
r($testcaseTest->setMenuForCaseEditTest($case, 0, 'execution')) && p('tab,executionID') && e('execution,2'); // 步骤4：execution标签页但executionID为0时使用case的execution
r($testcaseTest->setMenuForCaseEditTest($case, 0, 'other')) && p('tab,projectID,executionID') && e('other,~~,~~'); // 步骤5：无效标签页情况