#!/usr/bin/env php
<?php

/**

title=测试 docModel->upgradeCustomTemplateTypes();
timeout=0
cid=1

- 自定义模板1
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @1
 - 属性grade @2
 - 属性path @,2,
- 自定义模板2
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @1
 - 属性grade @2
 - 属性path @,3,
- 自定义模板4
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @1
 - 属性grade @2
 - 属性path @,4,
- 自定义模板5
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @1
 - 属性grade @2
 - 属性path @,5,
- 自定义模板6
 - 属性type @docTemplate
 - 属性root @2
 - 属性parent @1
 - 属性grade @2
 - 属性path @,6,

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

$docTester = new docTest();
r($docTester->upgradeTemplateTypesTest(2))  && p('type|root|parent|grade|path', '|') && e('docTemplate|2|1|2|,2,'); // 自定义模板1
r($docTester->upgradeTemplateTypesTest(3))  && p('type|root|parent|grade|path', '|') && e('docTemplate|2|1|2|,3,'); // 自定义模板2
r($docTester->upgradeTemplateTypesTest(4))  && p('type|root|parent|grade|path', '|') && e('docTemplate|2|1|2|,4,'); // 自定义模板4
r($docTester->upgradeTemplateTypesTest(5))  && p('type|root|parent|grade|path', '|') && e('docTemplate|2|1|2|,5,'); // 自定义模板5
r($docTester->upgradeTemplateTypesTest(6))  && p('type|root|parent|grade|path', '|') && e('docTemplate|2|1|2|,6,'); // 自定义模板6