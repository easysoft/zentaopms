#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getSubLibGroups();
cid=1
pid=1

all查询 >> 0
产品库查询 >> 产品主库;附件库
执行库查询 >> 0
产品库查询统计 >> 2

*/
global $tester;
$doc = $tester->loadModel('doc');

$types  = array('all', 'product', 'execution');
$idList = array('17', '1');

r($doc->getSubLibGroups($types[0], $idList))        && p()            && e('0');              //all查询
r($doc->getSubLibGroups($types[1], $idList)[1])     && p('1;files')   && e('产品主库;附件库');//产品库查询
r($doc->getSubLibGroups($types[2], $idList))        && p()            && e('0');              //执行库查询
r(count($doc->getSubLibGroups($types[1], $idList))) && p()            && e('2');              //产品库查询统计