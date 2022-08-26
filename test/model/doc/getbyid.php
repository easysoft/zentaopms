#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 docModel->getById();
cid=1
pid=1



*/
global $tester;
$doc = $tester->loadModel('doc');

$docIDList   = array('0', '17', '1');
$versionList = array('0', '1', '2');

r($doc->getById($docIDList[1], $versionList[0])) && p('product;title')   && e('17;文档标题17'); //正常根据id查询doc
r($doc->getById($docIDList[0], $versionList[0])) && p()                  && e('0');             //查询不存在的doc
r($doc->getById($docIDList[2], $versionList[1])) && p('version;content') && e('2;文档正文1');   //查询版本为1的文档内容
r($doc->getById($docIDList[2], $versionList[2])) && p('version;content') && e('2;文档正文901'); //查询版本为2的文档内容