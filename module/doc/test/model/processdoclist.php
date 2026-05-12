#!/usr/bin/env php
<?php

/**

title=测试 docModel->processDocList();
timeout=0
cid=16190

- 传入空数组结果数量 @0
- 传入已编码文档第1条的title属性 @文档标题 & 特殊字符
- 传入已编码文档第1条的keywords属性 @关键词 & 符号
- 传入已编码文档第1条的hasContent属性 @1
- 传入文档和根文档第2条的title属性 @合并文档
- 传入文档和根文档第3条的title属性 @根文档

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('docaction')->gen(0);
su('admin');

function buildDoc($id, $title, $keywords = '', $content = '')
{
    $doc = new stdclass();
    $doc->id         = $id;
    $doc->lib        = '1';
    $doc->module     = '0';
    $doc->deleted    = '0';
    $doc->title      = $title;
    $doc->keywords   = $keywords;
    $doc->content    = $content;
    $doc->path       = ',' . $id . ',';
    $doc->acl        = 'open';
    $doc->addedBy    = 'admin';
    $doc->users      = '';
    $doc->readUsers  = '';
    $doc->groups     = '';
    $doc->readGroups = '';
    return $doc;
}

$docTest = new docModelTest();

$result1 = $docTest->processDocListTest(array(), array(), 'mine');
r(count($result1)) && p() && e('0');

$docs = array(1 => buildDoc(1, '文档标题 &amp; 特殊字符', '关键词 &amp; 符号', 'https://example.com'));
$result2 = $docTest->processDocListTest($docs, array(), 'mine');
r($result2) && p('1:title')      && e('文档标题 & 特殊字符');
r($result2) && p('1:keywords')   && e('关键词 & 符号');
r($result2) && p('1:hasContent') && e('1');

$docs2   = array(2 => buildDoc(2, '合并文档'));
$rootDocs = array(3 => buildDoc(3, '根文档'));
$result3 = $docTest->processDocListTest($docs2, $rootDocs, 'mine');
r($result3) && p('2:title') && e('合并文档');
r($result3) && p('3:title') && e('根文档');
