#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 docModel->getLibById();
cid=1
pid=1

正常查询文档库 >> 产品主库
查询不存在的文档库 >> 0

*/
global $tester;
$doc = $tester->loadModel('doc');

$docLibIDList = array('0', '17');

r($doc->getLibById($docLibIDList[1])) && p('name') && e('产品主库');//正常查询文档库
r($doc->getLibById($docLibIDList[0])) && p()       && e('0');       //查询不存在的文档库