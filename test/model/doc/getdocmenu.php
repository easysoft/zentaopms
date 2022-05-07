#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getDocMenu();
cid=1
pid=1



*/
global $tester;
$doc = $tester->loadModel('doc');

$docIDList   = array('0', '17');
$parents     = array('0', '3637');
$orderBy     = array('name_asc', 'id_desc');
$browseTypes = array('collectedbyme', '');

r($doc->getDocMenu($docIDList[1], $parents[0], $orderBy[0], $browseTypes[1]))        && p('3637:name,parent') && e('目录17,0');     //无子目录查询
r($doc->getDocMenu($docIDList[1], $parents[1], $orderBy[0], $browseTypes[1]))        && p('3754:name,parent') && e('子目录34,3637');//有子目录查询
r(count($doc->getDocMenu($docIDList[1], $parents[1], $orderBy[0], $browseTypes[1]))) && p()                   && e('2');            //子目录查询统计
r($doc->getDocMenu($docIDList[0], $parents[0], $orderBy[0], $browseTypes[0]))        && p()                   && e('0');            //无效数据查询