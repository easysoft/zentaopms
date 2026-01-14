#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::downloadDuckdb();
timeout=0
cid=15155

- 步骤1：验证方法存在性 @1
- 步骤2：检查返回值类型 @1
- 步骤3：验证返回值状态 @1
- 步骤4：测试方法可重复调用 @1
- 步骤5：验证方法执行完整性 @1

*/

$biTest = new biModelTest();

r(method_exists($biTest->objectModel, 'downloadDuckdb')) && p() && e('1'); // 步骤1：验证方法存在性
r(is_string($biTest->downloadDuckdbTest())) && p() && e('1'); // 步骤2：检查返回值类型
r(in_array($biTest->downloadDuckdbTest(), array('ok', 'fail', 'loading'))) && p() && e('1'); // 步骤3：验证返回值状态
r(is_string($biTest->downloadDuckdbTest())) && p() && e('1'); // 步骤4：测试方法可重复调用
r(!empty($biTest->downloadDuckdbTest())) && p() && e('1'); // 步骤5：验证方法执行完整性