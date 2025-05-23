#!/usr/bin/env php
<?php
/**

title=测试 docModel->copyTemplate2OR();
timeout=0
cid=1

- 测试复制文档模板1
 - 第6条的vision属性 @or
 - 第6条的lib属性 @6
 - 第6条的module属性 @1
- 测试复制文档模板2
 - 第7条的vision属性 @or
 - 第7条的lib属性 @6
 - 第7条的module属性 @2
- 测试复制文档模板3
 - 第8条的vision属性 @or
 - 第8条的lib属性 @6
 - 第8条的module属性 @3
- 测试复制文档模板4
 - 第9条的vision属性 @or
 - 第9条的lib属性 @6
 - 第9条的module属性 @4
- 测试复制文档模板5
 - 第10条的vision属性 @or
 - 第10条的lib属性 @6
 - 第10条的module属性 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

$scope = zenData('doclib');
$scope->id->range('1-10');
$scope->type->range('template');
$scope->vision->range('rnd{4},or{3},lite{2},rnd');
$scope->name->range('产品,项目,执行,个人,市场,产品,个人,项目,个人,自定义范围');
$scope->main->range('1{9},0');

$scope->gen(10);
$doc = zenData('doc')->loadYaml('template');
$doc->templateType->range('custom1,custom2,custom3,custom4,custom5');
$doc->gen(5);

$module = zenData('module')->loadYaml('templatemodule');
$module->root->range('6');
$module->gen(5);

zenData('doccontent')->gen(5);
zenData('user')->gen(5);
su('admin');

$docTester = new docTest();
r($docTester->copyTemplate2ORTest(array(1))) && p('6:vision,lib,module')  && e('or,6,1'); // 测试复制文档模板1
r($docTester->copyTemplate2ORTest(array(2))) && p('7:vision,lib,module')  && e('or,6,2'); // 测试复制文档模板2
r($docTester->copyTemplate2ORTest(array(3))) && p('8:vision,lib,module')  && e('or,6,3'); // 测试复制文档模板3
r($docTester->copyTemplate2ORTest(array(4))) && p('9:vision,lib,module')  && e('or,6,4'); // 测试复制文档模板4
r($docTester->copyTemplate2ORTest(array(5))) && p('10:vision,lib,module') && e('or,6,5'); // 测试复制文档模板5
