#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getPrivDocs();
cid=1
pid=1

查询单条doc >> 1
查询多条doc >> 1;2
查询模块doc >> 1;2
不传入libid查询统计 >> 900

*/
global $tester;
$doc = $tester->loadModel('doc');

$libIDlist    = array('1', '2', '');
$moduleIDList = array('0','3621', '3622');

r($doc->getPrivDocs($libIDlist[0], $moduleIDList[0]))        && p('1')   && e('1');  //查询单条doc
r($doc->getPrivDocs($libIDlist, $moduleIDList[0]))           && p('1;2') && e('1;2');//查询多条doc
r($doc->getPrivDocs($libIDlist, $moduleIDList))              && p('1;2') && e('1;2');//查询模块doc
r(count($doc->getPrivDocs($libIDlist[2], $moduleIDList[0]))) && p()      && e('900');//不传入libid查询统计