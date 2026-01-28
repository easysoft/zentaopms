#!/usr/bin/env php
<?php

/**

title=测试 convertModel::tableExists();
timeout=0
cid=15797

- 步骤1：检查方法是否存在 @1
- 步骤2：检查方法是否可调用 @1
- 步骤3：检查存在的系统表user @1
- 步骤4：检查不存在的表名 @0
- 步骤5：检查空字符串表名 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$tester->loadModel('convert');

$convertTest = new convertModelTest();

r(method_exists($convertTest->objectModel, 'tableExists')) && p() && e('1'); // 步骤1：检查方法是否存在
r(is_callable(array($convertTest->objectModel, 'tableExists'))) && p() && e('1'); // 步骤2：检查方法是否可调用
r(!!$convertTest->tableExistsTest('zt_user')) && p() && e('1'); // 步骤3：检查存在的系统表user
r(!!$convertTest->tableExistsTest('zt_nonexistent_table_12345')) && p() && e('0'); // 步骤4：检查不存在的表名
r(!!$convertTest->tableExistsTest('')) && p() && e('1'); // 步骤5：检查空字符串表名