#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::checkDuckdbInstall();
timeout=0
cid=15153

- 步骤1：验证方法存在性 @1
- 步骤2：检查返回值为数组类型 @1
- 步骤3：验证返回结果包含所有必要字段 @1
- 步骤4：验证返回状态值合法性 @1
- 步骤5：测试方法可重复调用 @1

*/

$biTest = new biModelTest();

r(method_exists($biTest->instance, 'checkDuckdbInstall')) && p() && e('1'); // 步骤1：验证方法存在性
r(is_array($biTest->checkDuckdbInstallTest())) && p() && e('1'); // 步骤2：检查返回值为数组类型
r(array_key_exists('loading', $biTest->checkDuckdbInstallTest()) && array_key_exists('ok', $biTest->checkDuckdbInstallTest()) && array_key_exists('fail', $biTest->checkDuckdbInstallTest())) && p() && e('1'); // 步骤3：验证返回结果包含所有必要字段
r(in_array($biTest->checkDuckdbInstallTest()['duckdb'], array('ok', 'fail', 'loading'))) && p() && e('1'); // 步骤4：验证返回状态值合法性
r(is_array($biTest->checkDuckdbInstallTest())) && p() && e('1'); // 步骤5：测试方法可重复调用
