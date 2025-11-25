#!/usr/bin/env php
<?php
/**

title=测试 docModel->copyTemplate();
timeout=0
cid=16056

- 测试复制文档模板1
 - 第all条的0属性 @6
 - 第html条的0属性 @6
- 测试复制文档模板2
 - 第all条的0属性 @7
 - 第html条的0属性 @7
- 测试复制文档模板3
 - 第all条的0属性 @8
 - 第wiki条的0属性 @8
- 测试复制文档模板4
 - 第all条的0属性 @9
 - 第html条的0属性 @9
- 测试复制文档模板5
 - 第all条的0属性 @10
 - 第html条的0属性 @10

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
$doc->vision->range('rnd,rnd,or,or,or');
$doc->type->range('html,markdown,book,html,markdown');
$doc->gen(5);

$module = zenData('module')->loadYaml('templatemodule');
$module->root->range('6');
$module->gen(5);

zenData('doccontent')->gen(5);
zenData('user')->gen(5);
su('admin');

$docTester = new docTest();
r($docTester->copyTemplateTest(array(1))) && p('all:0;html:0') && e('6,6');   // 测试复制文档模板1
r($docTester->copyTemplateTest(array(2))) && p('all:0;html:0') && e('7,7');   // 测试复制文档模板2
r($docTester->copyTemplateTest(array(3))) && p('all:0;wiki:0') && e('8,8');   // 测试复制文档模板3
r($docTester->copyTemplateTest(array(4))) && p('all:0;html:0') && e('9,9');   // 测试复制文档模板4
r($docTester->copyTemplateTest(array(5))) && p('all:0;html:0') && e('10,10'); // 测试复制文档模板5
