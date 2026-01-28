#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::getFieldsForImport();
timeout=0
cid=15548

- 执行caselibTest模块的getFieldsForImportTest方法，参数是'count'  @9
- 执行caselibTest模块的getFieldsForImportTest方法 属性用例名称 @title
- 执行caselibTest模块的getFieldsForImportTest方法 属性所属模块 @module
- 执行caselibTest模块的getFieldsForImportTest方法 属性步骤 @stepDesc
- 执行caselibTest模块的getFieldsForImportTest方法 属性预期 @stepExpect

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$caselibTest = new caselibZenTest();

r($caselibTest->getFieldsForImportTest('count')) && p() && e('9');
r($caselibTest->getFieldsForImportTest()) && p('用例名称') && e('title');
r($caselibTest->getFieldsForImportTest()) && p('所属模块') && e('module');
r($caselibTest->getFieldsForImportTest()) && p('步骤') && e('stepDesc');
r($caselibTest->getFieldsForImportTest()) && p('预期') && e('stepExpect');