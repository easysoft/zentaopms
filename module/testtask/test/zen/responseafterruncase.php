#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::responseAfterRunCase();
timeout=0
cid=19242

- 执行testtaskTest模块的responseAfterRunCaseTest方法，参数是'success', null, null, 1, 1, ''  @success
- 执行testtaskTest模块的responseAfterRunCaseTest方法，参数是'success', null, null, 1, 2, ''  @success
- 执行testtaskTest模块的responseAfterRunCaseTest方法，参数是'success', null, null, 2, 1, ''  @success
- 执行testtaskTest模块的responseAfterRunCaseTest方法，参数是'success', null, null, 2, 2, ''  @success
- 执行testtaskTest模块的responseAfterRunCaseTest方法，参数是'success', null, null, 2, 3, 'yes'  @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->type->range('normal{3},branch{2}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->responseAfterRunCaseTest('success', null, null, 1, 1, ''))    && p() && e('success');
r($testtaskTest->responseAfterRunCaseTest('success', null, null, 1, 2, ''))    && p() && e('success');
r($testtaskTest->responseAfterRunCaseTest('success', null, null, 2, 1, ''))    && p() && e('success');
r($testtaskTest->responseAfterRunCaseTest('success', null, null, 2, 2, ''))    && p() && e('success');
r($testtaskTest->responseAfterRunCaseTest('success', null, null, 2, 3, 'yes')) && p() && e('success');
