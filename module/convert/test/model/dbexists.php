#!/usr/bin/env php
<?php

/**

title=测试 convertModel::dbExists();
timeout=0
cid=15769

- 步骤1：检查方法是否存在 @1
- 步骤2：检查方法是否可调用 @1
- 步骤3：测试空数据库名称 @0
- 步骤4：测试无效数据库名称（数字开头） @0
- 步骤5：测试无效数据库名称（特殊字符） @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

global $tester;
$tester->loadModel('convert');

$convertTest = new convertTest();

r(method_exists($convertTest->objectModel, 'dbExists')) && p() && e('1'); // 步骤1：检查方法是否存在
r(is_callable(array($convertTest->objectModel, 'dbExists'))) && p() && e('1'); // 步骤2：检查方法是否可调用
r($convertTest->dbExistsTest('')) && p() && e('0'); // 步骤3：测试空数据库名称
r($convertTest->dbExistsTest('123invalid')) && p() && e('0'); // 步骤4：测试无效数据库名称（数字开头）
r($convertTest->dbExistsTest('test-db')) && p() && e('0'); // 步骤5：测试无效数据库名称（特殊字符）