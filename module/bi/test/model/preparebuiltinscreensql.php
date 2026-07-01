#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareBuiltinScreenSQL();
timeout=0
cid=15200

- 步骤1：测试insert操作返回数组 @1
- 步骤2：测试update操作返回数组 @1
- 步骤3：验证insert生成SQL内容 @1
- 步骤4：验证update生成SQL内容 @1
- 步骤5：测试无效操作参数处理 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('screen');
$table->id->range('1-10');
$table->name->range('test screen{1-10}');
$table->status->range('published');
$table->builtin->range('1');
$table->gen(0);

su('admin');

$biTest = new biModelTest();

$insertSQLs  = $biTest->prepareBuiltinScreenSQLTest('insert');
$updateSQLs  = $biTest->prepareBuiltinScreenSQLTest('update');
$invalidSQLs = $biTest->prepareBuiltinScreenSQLTest('invalid');

r(is_array($insertSQLs)) && p() && e('1'); // 步骤1：测试insert操作返回数组
r(is_array($updateSQLs)) && p() && e('1'); // 步骤2：测试update操作返回数组
r(!empty($insertSQLs) && strpos($insertSQLs[0], 'INSERT INTO') !== false) && p() && e('1'); // 步骤3：验证insert生成SQL内容
r(!empty($updateSQLs) && strpos($updateSQLs[0], 'zt_screen') !== false) && p() && e('1'); // 步骤4：验证update生成SQL内容
r(is_array($invalidSQLs) && count($invalidSQLs) > 0) && p() && e('1'); // 步骤5：测试无效操作参数处理
