#!/usr/bin/env php
<?php

/**

title=测试 docModel->upgradeCustomTemplateTypes();
timeout=0
cid=16165

- 内置计划 @1
- 内置需求 @1
- 内置设计 @1
- 内置开发 @1
- 内置测试 @1
- 内置说明 @1
- 内置其他 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('module')->gen(0);
zenData('doc')->loadYaml('template')->gen(20);
zenData('user')->gen(5);
su('admin');

$scope = zenData('config');
$scope->id->range('1-3');
$scope->vision->range('rnd,or,lite');
$scope->owner->range('system');
$scope->module->range('doc');
$scope->section->range('');
$scope->key->range('builtInScopeMaps');
$scope->value->range('`{"product":1,"project":2,"execution":3,"personal":4}`,`{"market":5,"product":6,"personal":7}`,`{"project":8,"personal":9}`');
$scope->gen(3);

$docTester = new docTest();
r($docTester->upgradeTemplateTypesTest(1, '计划'))  && p() && e('1'); // 内置计划
r($docTester->upgradeTemplateTypesTest(2, '需求'))  && p() && e('1'); // 内置需求
r($docTester->upgradeTemplateTypesTest(3, '设计'))  && p() && e('1'); // 内置设计
r($docTester->upgradeTemplateTypesTest(4, '开发'))  && p() && e('1'); // 内置开发
r($docTester->upgradeTemplateTypesTest(5, '测试'))  && p() && e('1'); // 内置测试
r($docTester->upgradeTemplateTypesTest(6, '说明'))  && p() && e('1'); // 内置说明
r($docTester->upgradeTemplateTypesTest(7, '其他'))  && p() && e('1'); // 内置其他
