#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::getSqlByMonth();
timeout=0
cid=15181

- 步骤1：默认参数测试返回数组且长度为1 @1
- 步骤2：返回数组类型验证 @1
- 步骤3：不同年月格式测试 @1
- 步骤4：验证SQL结构 @1
- 步骤5：再次验证方法调用稳定性 @1

*/

$biTest = new biModelTest();

r(is_array($biTest->getSqlByMonthTest()) && count($biTest->getSqlByMonthTest()) == 1) && p() && e('1'); // 步骤1：默认参数测试返回数组且长度为1
r(is_array($biTest->getSqlByMonthTest('Y', 'm'))) && p() && e('1'); // 步骤2：返回数组类型验证
$result = $biTest->getSqlByMonthTest('y', 'n');
r(is_array($result) && count($result) == 1) && p() && e('1'); // 步骤3：不同年月格式测试
$result = $biTest->getSqlByMonthTest();
$sql = current($result);
r(strpos($sql, 'select * from zt_action where') !== false && strpos($sql, 'TIMESTAMP') !== false) && p() && e('1'); // 步骤4：验证SQL结构
r(is_array($biTest->getSqlByMonthTest()) && count($biTest->getSqlByMonthTest()) == 1) && p() && e('1'); // 步骤5：再次验证方法调用稳定性