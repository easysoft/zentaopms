#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->saveDraft();
cid=1
pid=1



*/
$docIDList = array('17', '118', '217');
$content   = array('content' => '测试');

$doc = new docTest();
r($doc->saveDraftTest($docIDList[0], $content)) && p('17:draft')  && e('测试');//产品文档内容保存
r($doc->saveDraftTest($docIDList[1], $content)) && p('118:draft') && e('测试');//项目文档内容保存
r($doc->saveDraftTest($docIDList[2], $content)) && p('217:draft') && e('测试');//执行文档内容保存
r($doc->saveDraftTest($docIDList[0]))           && p('17:draft')  && e('测试');//文档无内容保存

