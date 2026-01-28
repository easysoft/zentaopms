#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->upgradeDocTemplate();
timeout=0
cid=19560

- 更新文档模板1
 - 属性version @2
 - 属性type @doc
- 更新文档模板2
 - 属性version @2
 - 属性type @doc
- 更新文档模板3
 - 属性version @2
 - 属性type @doc
- 更新文档模板4
 - 属性version @2
 - 属性type @doc
- 更新文档模板5
 - 属性version @2
 - 属性type @doc

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->loadYaml('user')->gen(5);
su('admin');

$doc = zenData('doc');
$doc->id->range('1-10');
$doc->title->range('文档模板1,文档模板2,文档模板3,文档模板4,文档模板5,文档模板6,文档模板7,文档模板8,文档模板9,文档模板10');
$doc->module->range('');
$doc->lib->range('');
$doc->version->range('1');
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

$upgrade = new upgradeModelTest();
r($upgrade->upgradeDocTemplateTest(1, 1)) && p('version,type') && e('2,doc'); // 更新文档模板1
r($upgrade->upgradeDocTemplateTest(2, 1)) && p('version,type') && e('2,doc'); // 更新文档模板2
r($upgrade->upgradeDocTemplateTest(3, 1)) && p('version,type') && e('2,doc'); // 更新文档模板3
r($upgrade->upgradeDocTemplateTest(4, 1)) && p('version,type') && e('2,doc'); // 更新文档模板4
r($upgrade->upgradeDocTemplateTest(5, 1)) && p('version,type') && e('2,doc'); // 更新文档模板5
