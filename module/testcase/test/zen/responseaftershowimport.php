#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::responseAfterShowImport();
timeout=0
cid=0

- 步骤1：DAO错误情况属性result @fail
- 步骤2：最后一页项目标签属性result @success
- 步骤3：最后一页普通标签属性result @success
- 步骤4：非最后一页属性result @success
- 步骤5：自定义消息
 - 属性result @success
 - 属性message @自定义成功消息

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('case');
$table->id->range('1-10');
$table->product->range('1-3');
$table->title->range('测试用例1,测试用例2,测试用例3');
$table->status->range('normal');
$table->openedBy->range('admin');
$table->openedDate->range('`2023-01-01 00:00:00`');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->responseAfterShowImportTest(1, '0', 10, '/tmp/test.csv', '', true)) && p('result') && e('fail'); // 步骤1：DAO错误情况
r($testcaseTest->responseAfterShowImportTest(1, '0', 10, '/tmp/test.csv', '', false, true)) && p('result') && e('success'); // 步骤2：最后一页项目标签
r($testcaseTest->responseAfterShowImportTest(1, '0', 10, '/tmp/test.csv', '', false, false)) && p('result') && e('success'); // 步骤3：最后一页普通标签
r($testcaseTest->responseAfterShowImportTest(1, '0', 10, '/tmp/test.csv', '', false, false, false)) && p('result') && e('success'); // 步骤4：非最后一页
r($testcaseTest->responseAfterShowImportTest(1, '0', 10, '/tmp/test.csv', '自定义成功消息', false, false, true)) && p('result,message') && e('success,自定义成功消息'); // 步骤5：自定义消息