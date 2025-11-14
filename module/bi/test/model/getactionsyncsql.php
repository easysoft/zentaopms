#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::getActionSyncSql();
timeout=0
cid=15160

- 步骤1：测试当前月份范围返回数组且长度为1 @1
- 步骤2：测试当前月份范围返回数组类型验证 @1
- 步骤3：测试无效参数当作current处理 @1
- 步骤4：测试默认参数等同current @1
- 步骤5：验证SQL语句包含正确结构 @1

*/

$biTest = new biTest();

r(is_array($biTest->getActionSyncSqlTest('current')) && count($biTest->getActionSyncSqlTest('current')) == 1) && p() && e('1'); // 步骤1：测试当前月份范围返回数组且长度为1
r(is_array($biTest->getActionSyncSqlTest('current'))) && p() && e('1'); // 步骤2：测试当前月份范围返回数组类型验证
r(is_array($biTest->getActionSyncSqlTest('invalid')) && count($biTest->getActionSyncSqlTest('invalid')) == 1) && p() && e('1'); // 步骤3：测试无效参数当作current处理
r(is_array($biTest->getActionSyncSqlTest()) && count($biTest->getActionSyncSqlTest()) == 1) && p() && e('1'); // 步骤4：测试默认参数等同current
$result = $biTest->getActionSyncSqlTest('current');
$sql = current($result);
r(strpos($sql, 'select * from zt_action where') !== false && strpos($sql, 'TIMESTAMP') !== false) && p() && e('1'); // 步骤5：验证SQL语句包含正确结构