#!/usr/bin/env php
<?php

/**

title=测试 docModel->checkApiLibName();
timeout=0
cid=1

- 一级模板类型 @1
- short为空的类型 @0
- 类型为doc的类型 @0
- 二级模板类型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

$module1 = new stdclass();
$module1->short = 'Product plan';
$module1->type  = 'docTemplate';

$module2 = new stdclass();
$module2->short = '';
$module2->type  = 'docTemplate';

$module3 = new stdclass();
$module3->short = 'Product plan';
$module3->type  = 'doc';

$module4 = new stdclass();
$module4->short = 'Software product plan';
$module4->type  = 'docTemplate';

$docTester = new docTest();
r($docTester->isBuiltinTemplateModuleTest($module1)) && p() && e(1); //一级模板类型
r($docTester->isBuiltinTemplateModuleTest($module2)) && p() && e(0); //short为空的类型
r($docTester->isBuiltinTemplateModuleTest($module3)) && p() && e(0); //类型为doc的类型
r($docTester->isBuiltinTemplateModuleTest($module4)) && p() && e(1); //二级模板类型