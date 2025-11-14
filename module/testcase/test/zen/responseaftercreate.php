#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::responseAfterCreate();
timeout=0
cid=19108

- 步骤1：正常QA tab调用属性result @success
- 步骤2：JSON视图调用属性type @json
- 步骤3：Project tab调用属性location @project-testcase
- 步骤4：带模块ID的调用属性moduleParam @2
- 步骤5：使用session链接属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$case = zenData('case');
$case->id->range('1-10');
$case->product->range('1-3');
$case->module->range('1-5');
$case->title->range('测试用例{1-10}');
$case->type->range('feature,performance,config,install');
$case->status->range('normal,wait,blocked');
$case->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->responseAfterCreateTest(1, 0, 'html', 'qa', false)) && p('result') && e('success'); // 步骤1：正常QA tab调用
r($testcaseTest->responseAfterCreateTest(2, 0, 'json', 'qa', false)) && p('type') && e('json'); // 步骤2：JSON视图调用
r($testcaseTest->responseAfterCreateTest(3, 0, 'html', 'project', false)) && p('location') && e('project-testcase'); // 步骤3：Project tab调用
r($testcaseTest->responseAfterCreateTest(4, 2, 'html', 'qa', false)) && p('moduleParam') && e('2'); // 步骤4：带模块ID的调用
r($testcaseTest->responseAfterCreateTest(5, 0, 'html', 'qa', true)) && p('result') && e('success'); // 步骤5：使用session链接