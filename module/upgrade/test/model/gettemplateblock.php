#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getTemplateBlock();
timeout=0
cid=19529

- 获取概要设计区块名称属性blockTitle @全部的概要设计
- 获取详细设计区块名称属性blockTitle @全部的详细设计
- 获取数据库设计区块名称属性blockTitle @全部的数据库设计
- 获取接口设计区块名称属性blockTitle @全部的接口设计
- 获取项目用例区块名称属性blockTitle @全部的项目用例

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
$doc->version->range('1');
$doc->type->range('book{5},html{5}');
$doc->templateType->range('HLDS,DDS,DBDS,ADS,ITTC,``{5}');
$doc->chapterType->range('``{5},input{5}');
$doc->template->range('``{5},1,2,3,4,5');
$doc->parent->range('0{5},1{5}');
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
$upgrade->loadModel('doc');
r($upgrade->getTemplateBlock(1)) && p('blockTitle') && e('全部的概要设计');   // 获取概要设计区块名称
r($upgrade->getTemplateBlock(2)) && p('blockTitle') && e('全部的详细设计');   // 获取详细设计区块名称
r($upgrade->getTemplateBlock(3)) && p('blockTitle') && e('全部的数据库设计'); // 获取数据库设计区块名称
r($upgrade->getTemplateBlock(4)) && p('blockTitle') && e('全部的接口设计');   // 获取接口设计区块名称
r($upgrade->getTemplateBlock(5)) && p('blockTitle') && e('全部的项目用例');   // 获取项目用例区块名称
