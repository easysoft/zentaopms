#!/usr/bin/env php
<?php

/**

title=测试 docModel->validateDocUrl();
timeout=0
cid=16189

- 传入空内容第0条的content属性 @『文档 URL』不能为空。
- 传入非法URL第0条的content属性 @『文档 URL』应当为合法的URL。
- 传入包含&amp;的URL解码后content @https://ipd.hjq.oop.cc/index.php?m=execution&f=task
- 传入包含&amp;&amp;的URL解码后content @https://ipd.hjq.oop.cc/index.php?m=execution&f=task&page=1
- 传入不包含&amp;的URL解码后content @https://example.com/index.php?m=execution&f=task

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$docTest = new docModelTest();

$doc1 = new stdclass();
$doc1->content = '';
r($docTest->validateDocUrlTest($doc1)) && p('content') && e('『文档 URL』不能为空。');

$doc2 = new stdclass();
$doc2->content = 'not a url';
r($docTest->validateDocUrlTest($doc2)) && p('content') && e('『文档 URL』应当为合法的URL。');

$doc3 = new stdclass();
$doc3->content = 'https://ipd.hjq.oop.cc/index.php?m=execution&amp;f=task';
r($docTest->validateDocUrlTest($doc3));
r($doc3->content) && p('解码后content') && e('https://ipd.hjq.oop.cc/index.php?m=execution&f=task');

$doc4 = new stdclass();
$doc4->content = 'https://ipd.hjq.oop.cc/index.php?m=execution&amp;f=task&amp;page=1';
r($docTest->validateDocUrlTest($doc4));
r($doc4->content) && p('解码后content') && e('https://ipd.hjq.oop.cc/index.php?m=execution&f=task&page=1');

$doc5 = new stdclass();
$doc5->content = 'https://example.com/index.php?m=execution&f=task';
r($docTest->validateDocUrlTest($doc5));
r($doc5->content) && p('解码后content') && e('https://example.com/index.php?m=execution&f=task');
