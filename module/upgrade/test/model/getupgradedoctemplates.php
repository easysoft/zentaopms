#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getUpgradeDocTemplates();
timeout=0
cid=0

- 获取旧文档模板67
 -  @6
 - 属性1 @7
- 获取旧文档模板89
 - 属性2 @8
 - 属性3 @9
- 获取旧文档模板12
 -  @1
 - 属性1 @2
- 获取旧文档模板34
 - 属性2 @3
 - 属性3 @4
- 获取旧文档模板12
 -  @1
 - 属性1 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

zenData('user')->loadYaml('user')->gen(5);
su('admin');

$doc = zenData('doc');
$doc->id->range('1-10');
$doc->title->range('文档模板1,文档模板2,文档模板3,文档模板4,文档模板5,文档模板6,文档模板7,文档模板8,文档模板9,文档模板10');
$doc->module->range('');
$doc->lib->range('');
$doc->type->range('book{5},html{5}');
$doc->templateType->range('1-10');
$doc->gen(10);

$docContent = zenData('doccontent');
$docContent->id->range('1-10');
$docContent->doc->range('1-10');
$docContent->type->range('html');
$docContent->title->range('文档模板1,文档模板2,文档模板3,文档模板4,文档模板5,文档模板6,文档模板7,文档模板8,文档模板9,文档模板10');
$docContent->version->range('1');
$docContent->gen(10);

global $tester;
$upgrade = $tester->loadModel('upgrade');
r($upgrade->getUpgradeDocTemplates()['html']) && p('0,1') && e('6,7'); // 获取旧文档模板67
r($upgrade->getUpgradeDocTemplates()['html']) && p('2,3') && e('8,9'); // 获取旧文档模板89
r($upgrade->getUpgradeDocTemplates()['wiki']) && p('0,1') && e('1,2'); // 获取旧文档模板12
r($upgrade->getUpgradeDocTemplates()['wiki']) && p('2,3') && e('3,4'); // 获取旧文档模板34
r($upgrade->getUpgradeDocTemplates()['all'])  && p('0,1') && e('1,2'); // 获取旧文档模板12
