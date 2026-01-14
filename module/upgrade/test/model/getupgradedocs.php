#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getUpgradeDocs();
timeout=0
cid=0

- 获取旧文档45
 -  @4
 - 属性1 @5
- 获取旧文档67
 -  @6
 - 属性1 @7
- 获取旧文档89
 - 属性2 @8
 - 属性3 @9
- 获取旧文档12
 -  @1
 - 属性1 @2
- 获取旧文档34
 - 属性2 @3
 - 属性3 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->loadYaml('user')->gen(5);
su('admin');

$doc = zenData('doc');
$doc->id->range('1-10');
$doc->title->range('文档1,文档2,文档3,文档4,文档5,文档6,文档7,文档8,文档9,文档10');
$doc->version->range('1');
$doc->module->range('1');
$doc->gen(10);

$docContent = zenData('doccontent');
$docContent->id->range('1-10');
$docContent->doc->range('1-10');
$docContent->type->range('doc{5},html{5}');
$docContent->content->range('``{3},docContent{2},htmlContent{5}');
$docContent->title->range('文档1,文档2,文档3,文档4,文档5,文档6,文档7,文档8,文档9,文档10');
$docContent->version->range('1');
$docContent->gen(10);

$docLib = zenData('doclib');
$docLib->id->range('1-5');
$docLib->type->range('book');
$docLib->name->range('book1,book2,book3,book4,book5');
$docLib->gen(5);

$upgrade = new upgradeModelTest();
r($upgrade->getUpgradeDocsTest()['doc'])  && p('0,1') && e('4,5'); // 获取旧文档45
r($upgrade->getUpgradeDocsTest()['html']) && p('0,1') && e('6,7'); // 获取旧文档67
r($upgrade->getUpgradeDocsTest()['html']) && p('2,3') && e('8,9'); // 获取旧文档89
r($upgrade->getUpgradeDocsTest()['wiki']) && p('0,1') && e('1,2'); // 获取旧文档12
r($upgrade->getUpgradeDocsTest()['wiki']) && p('2,3') && e('3,4'); // 获取旧文档34
