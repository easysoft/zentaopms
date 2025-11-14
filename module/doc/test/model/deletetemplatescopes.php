#!/usr/bin/env php
<?php

/**

title=测试 docModel->deleteTemplateScopes();
timeout=0
cid=16066

- 测试删除产品范围 @1
- 测试删除项目范围 @1
- 测试删除执行范围 @1
- 测试删除个人范围 @1
- 测试删除自定义范围 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

$scope = zenData('doclib');
$scope->id->range('1-10');
$scope->type->range('template');
$scope->vision->range('rnd{4},or{3},lite{2},rnd');
$scope->name->range('产品,项目,执行,个人,市场,项目,个人,产品,个人,自定义范围');
$scope->main->range('1{9},0');
$scope->gen(10);

$docTester = new docTest();
r($docTester->deleteTemplateScopesTest(1))  && p()  && e('1'); // 测试删除产品范围
r($docTester->deleteTemplateScopesTest(2))  && p()  && e('1'); // 测试删除项目范围
r($docTester->deleteTemplateScopesTest(3))  && p()  && e('1'); // 测试删除执行范围
r($docTester->deleteTemplateScopesTest(4))  && p()  && e('1'); // 测试删除个人范围
r($docTester->deleteTemplateScopesTest(10)) && p()  && e('1'); // 测试删除自定义范围
