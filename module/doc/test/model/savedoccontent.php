#!/usr/bin/env php
<?php

/**

title=测试 docModel->saveDocContent();
cid=16149

- 保存文档1的内容
 - 属性content @这是文档1的content
 - 属性rawContent @这是文档1的rawContent
 - 属性title @这是文档1的title
- 保存文档2的内容
 - 属性content @这是文档2的content
 - 属性rawContent @这是文档2的rawContent
 - 属性title @这是文档2的title
- 保存文档3的内容
 - 属性content @这是文档3的content
 - 属性rawContent @这是文档3的rawContent
 - 属性title @这是文档3的title
- 保存文档4的内容
 - 属性content @这是文档4的content
 - 属性rawContent @这是文档4的rawContent
 - 属性title @这是文档4的title
- 保存文档5的内容
 - 属性content @这是文档5的content
 - 属性rawContent @这是文档5的rawContent
 - 属性title @这是文档5的title

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doc')->gen(0);
zenData('doccontent')->gen(0);
zenData('user')->gen(5);

$doc1 = new stdClass();
$doc1->editedBy    = 'user1';
$doc1->rawContent  = '这是文档1的rawContent';
$doc1->content     = '这是文档1的content';
$doc1->title       = '这是文档1的title';
$doc1->contentType = 'doc';

$doc2 = new stdClass();
$doc2->editedBy    = 'user2';
$doc2->rawContent  = '这是文档2的rawContent';
$doc2->content     = '这是文档2的content';
$doc2->title       = '这是文档2的title';
$doc2->contentType = 'doc';

$doc3 = new stdClass();
$doc3->editedBy    = 'user3';
$doc3->rawContent  = '这是文档3的rawContent';
$doc3->content     = '这是文档3的content';
$doc3->title       = '这是文档3的title';
$doc3->contentType = 'doc';

$doc4 = new stdClass();
$doc4->editedBy    = 'user4';
$doc4->rawContent  = '这是文档4的rawContent';
$doc4->content     = '这是文档4的content';
$doc4->title       = '这是文档4的title';
$doc4->contentType = 'doc';

$doc5 = new stdClass();
$doc5->editedBy    = 'user5';
$doc5->rawContent  = '这是文档5的rawContent';
$doc5->content     = '这是文档5的content';
$doc5->title       = '这是文档5的title';
$doc5->contentType = 'doc';

global $tester;
$docTester = $tester->loadModel('doc');
r($docTester->saveDocContent(1, $doc1, 1)) && p('content,rawContent,title') && e('这是文档1的content,这是文档1的rawContent,这是文档1的title'); // 保存文档1的内容
r($docTester->saveDocContent(2, $doc2, 1)) && p('content,rawContent,title') && e('这是文档2的content,这是文档2的rawContent,这是文档2的title'); // 保存文档2的内容
r($docTester->saveDocContent(3, $doc3, 1)) && p('content,rawContent,title') && e('这是文档3的content,这是文档3的rawContent,这是文档3的title'); // 保存文档3的内容
r($docTester->saveDocContent(4, $doc4, 1)) && p('content,rawContent,title') && e('这是文档4的content,这是文档4的rawContent,这是文档4的title'); // 保存文档4的内容
r($docTester->saveDocContent(5, $doc5, 1)) && p('content,rawContent,title') && e('这是文档5的content,这是文档5的rawContent,这是文档5的title'); // 保存文档5的内容
