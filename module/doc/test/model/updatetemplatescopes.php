#!/usr/bin/env php
<?php

/**

title=测试 docModel->updateTemplateScopes();
timeout=0
cid=16163

- 测试修改产品范围名称属性1 @修改后的产品范围
- 测试修改项目范围名称属性2 @修改后的项目范围
- 测试修改执行范围名称属性3 @修改后的执行范围
- 测试修改个人范围名称属性4 @修改后的个人范围
- 测试修改自定义范围名称属性10 @修改后的自定义范围

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$scope = zenData('doclib');
$scope->id->range('1-10');
$scope->type->range('template');
$scope->vision->range('rnd{4},or{3},lite{2},rnd');
$scope->name->range('产品,项目,执行,个人,市场,项目,个人,产品,个人,自定义范围');
$scope->main->range('1{9},0');
$scope->gen(10);

$scopeList = array(array(1 => '修改后的产品范围'), array(2 => '修改后的项目范围'), array(3 => '修改后的执行范围'), array(4 => '修改后的个人范围'), array(10 => '修改后的自定义范围'));

$docTester = new docModelTest();
r($docTester->updateTemplateScopesTest($scopeList[0])) && p('1')  && e('修改后的产品范围');   // 测试修改产品范围名称
r($docTester->updateTemplateScopesTest($scopeList[1])) && p('2')  && e('修改后的项目范围');   // 测试修改项目范围名称
r($docTester->updateTemplateScopesTest($scopeList[2])) && p('3')  && e('修改后的执行范围');   // 测试修改执行范围名称
r($docTester->updateTemplateScopesTest($scopeList[3])) && p('4')  && e('修改后的个人范围');   // 测试修改个人范围名称
r($docTester->updateTemplateScopesTest($scopeList[4])) && p('10') && e('修改后的自定义范围'); // 测试修改自定义范围名称
