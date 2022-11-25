#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getToAndCcList();
cid=1
pid=1



*/
$docID = array('1', '2', '3');

$doc = new docTest();

r($doc->getToAndCcListTest($docID[0])) && p('0;1') && e('admin;'); //查询文档type为text的数据
r($doc->getToAndCcListTest($docID[1])) && p('0;1') && e('admin;'); //查询文档type为markdown的数据
r($doc->getToAndCcListTest($docID[2])) && p('0;1') && e('admin;'); //查询文档type为url的数据