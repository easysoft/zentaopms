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
 - 属性path @,2,
- 内置需求
 - 属性name @需求
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @0
 - 属性grade @1
 - 属性path @,3,
- 内置设计
 - 属性name @设计
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @0
 - 属性grade @1
 - 属性path @,4,
- 内置开发
 - 属性name @开发
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @0
 - 属性grade @1
 - 属性path @,5,
- 内置测试
 - 属性name @测试
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @0
 - 属性grade @1
 - 属性path @,6,
- 内置说明
 - 属性name @说明
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @0
 - 属性grade @1
 - 属性path @,7,
- 内置其他
 - 属性name @其他
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @0
 - 属性grade @1
 - 属性path @,8,
- 自定义模板6
 - 属性name @自定义模板6
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @8
 - 属性grade @2
 - 属性path @,8,9,

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doc')->loadYaml('template')->gen(20);
zenData('user')->gen(5);
su('admin');

$oldModule = zenData('lang');
$oldModule->id->range('1-10');
$oldModule->module->range('baseline');
$oldModule->section->range('objectList');
$oldModule->system->range(0);
$oldModule->key->range('module1,module2,module3,module4,module5,module6,module7,module8,module9,module10');
$oldModule->value->range('自定义模板1,自定义模板2,自定义模板3,自定义模板4,自定义模板5,自定义模板6,自定义模板7,自定义模板8,自定义模板9,自定义模板10');
$oldModule->gen(10);

$newModule = zenData('module');
$newModule->id->range(1);
$newModule->root->range(2);
$newModule->name->range('项目其他');
$newModule->path->range('`,1,`');
$newModule->grade->range(1);
$newModule->order->range(10);
$newModule->type->range('docTemplate');
$newModule->short->range('Project other');
$newModule->gen(1);

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
r($docTester->upgradeTemplateTypesTest(2))  && p('name|type|root|parent|grade|path', '|') && e('计划|docTemplate|2|0|1|,2,'); // 内置计划
r($docTester->upgradeTemplateTypesTest(3))  && p('name|type|root|parent|grade|path', '|') && e('需求|docTemplate|2|0|1|,3,'); // 内置需求
r($docTester->upgradeTemplateTypesTest(4))  && p('name|type|root|parent|grade|path', '|') && e('设计|docTemplate|2|0|1|,4,'); // 内置设计
r($docTester->upgradeTemplateTypesTest(5))  && p('name|type|root|parent|grade|path', '|') && e('开发|docTemplate|2|0|1|,5,'); // 内置开发
r($docTester->upgradeTemplateTypesTest(6))  && p('name|type|root|parent|grade|path', '|') && e('测试|docTemplate|2|0|1|,6,'); // 内置测试
r($docTester->upgradeTemplateTypesTest(7))  && p('name|type|root|parent|grade|path', '|') && e('说明|docTemplate|2|0|1|,7,'); // 内置说明
r($docTester->upgradeTemplateTypesTest(8))  && p('name|type|root|parent|grade|path', '|') && e('其他|docTemplate|2|0|1|,8,'); // 内置其他
r($docTester->upgradeTemplateTypesTest(9))  && p('name|type|root|parent|grade|path', '|') && e('自定义模板6|docTemplate|2|8|2|,8,9,'); // 自定义模板6
