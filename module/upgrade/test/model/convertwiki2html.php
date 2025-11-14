#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->convertWiki2Html();
timeout=0
cid=19509

- 转换文档模板6 @<p>文档模板6</p>
- 转换文档模板7 @<p>文档模板7</p>
- 转换文档模板8 @<p>文档模板8</p>
- 转换文档模板9 @<p>文档模板9</p>
- 转换文档模板10 @<p>文档模板10</p>

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
$doc->templateType->range('1{5},``{5}');
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
r($upgrade->convertWiki2Html(1, 6))  && p() && e('<p>文档模板6</p>');  // 转换文档模板6
r($upgrade->convertWiki2Html(1, 7))  && p() && e('<p>文档模板7</p>');  // 转换文档模板7
r($upgrade->convertWiki2Html(1, 8))  && p() && e('<p>文档模板8</p>');  // 转换文档模板8
r($upgrade->convertWiki2Html(1, 9))  && p() && e('<p>文档模板9</p>');  // 转换文档模板9
r($upgrade->convertWiki2Html(1, 10)) && p() && e('<p>文档模板10</p>'); // 转换文档模板10
