#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';
su('admin');

/**

title=测试 docModel->checkAutoloadPage();
cid=1
pid=1



*/
$docID = array('1', '3', '');

$doc = new docTest();

r($doc->checkAutoloadPageTest($docID[0])) && p() && e('1'); //查询文档type为text时数据
r($doc->checkAutoloadPageTest($docID[1])) && p() && e('1'); //查询文档type为markdown时数据
r($doc->checkAutoloadPageTest($docID[2])) && p() && e('1'); //查询文档不存在时数据