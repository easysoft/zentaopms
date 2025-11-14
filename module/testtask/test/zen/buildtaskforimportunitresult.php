#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::buildTaskForImportUnitResult();
cid=19230

- 测试步骤1：正常产品ID测试 >> 期望返回构建的任务对象
- 测试步骤2：空产品ID测试 >> 期望返回构建的任务对象
- 测试步骤3：不存在的产品ID测试 >> 期望返回构建的任务对象
- 测试步骤4：大产品ID测试 >> 期望返回构建的任务对象
- 测试步骤5：验证必须字段设置 >> 期望product、auto字段正确设置

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(10);

su('admin');

$testtaskZenTest = new testtaskZenTest();

r($testtaskZenTest->buildTaskForImportUnitResultTest(1)) && p('product,auto') && e('1,unit');
r($testtaskZenTest->buildTaskForImportUnitResultTest(0)) && p('product,auto') && e('0,unit');
r($testtaskZenTest->buildTaskForImportUnitResultTest(999)) && p('product,auto') && e('999,unit');
r($testtaskZenTest->buildTaskForImportUnitResultTest(5)) && p('product,auto,name') && e('5,unit,JUnit测试导入');
r($testtaskZenTest->buildTaskForImportUnitResultTest(1)) && p('product,auto') && e('1,unit');