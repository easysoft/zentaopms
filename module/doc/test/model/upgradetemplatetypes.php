#!/usr/bin/env php
<?php

/**

title=测试 docModel->upgradeCustomTemplateTypes();
timeout=0
cid=1

- 内置计划
 - 属性name @计划
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @0
 - 属性grade @1
 - 属性path @,1,
- 内置需求
 - 属性name @需求
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @0
 - 属性grade @1
 - 属性path @,2,
- 内置设计
 - 属性name @设计
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @0
 - 属性grade @1
 - 属性path @,3,
- 内置开发
 - 属性name @开发
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @0
 - 属性grade @1
 - 属性path @,4,
- 内置测试
 - 属性name @测试
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @0
 - 属性grade @1
 - 属性path @,5,
- 内置说明
 - 属性name @说明
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @0
 - 属性grade @1
 - 属性path @,6,
- 内置其他
 - 属性name @其他
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @0
 - 属性grade @1
 - 属性path @,7,

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
r($docTester->upgradeTemplateTypesTest(1))  && p('name|type|root|parent|grade|path', '|') && e('计划|docTemplate|2|0|1|,1,'); // 内置计划
r($docTester->upgradeTemplateTypesTest(2))  && p('name|type|root|parent|grade|path', '|') && e('需求|docTemplate|2|0|1|,2,'); // 内置需求
r($docTester->upgradeTemplateTypesTest(3))  && p('name|type|root|parent|grade|path', '|') && e('设计|docTemplate|2|0|1|,3,'); // 内置设计
r($docTester->upgradeTemplateTypesTest(4))  && p('name|type|root|parent|grade|path', '|') && e('开发|docTemplate|2|0|1|,4,'); // 内置开发
r($docTester->upgradeTemplateTypesTest(5))  && p('name|type|root|parent|grade|path', '|') && e('测试|docTemplate|2|0|1|,5,'); // 内置测试
r($docTester->upgradeTemplateTypesTest(6))  && p('name|type|root|parent|grade|path', '|') && e('说明|docTemplate|2|0|1|,6,'); // 内置说明
r($docTester->upgradeTemplateTypesTest(7))  && p('name|type|root|parent|grade|path', '|') && e('其他|docTemplate|2|0|1|,7,'); // 内置其他
