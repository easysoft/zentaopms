#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 docModel->getDocs();
cid=1
pid=1

正常获取文档库下的文档 >> 1,文档标题1
获取文档目录下的文档 >> 2,3622
libID为空查询 >> 900

*/
global $tester;
$doc = $tester->loadModel('doc');

$libIDlist    = array('1', '2', '');
$moduleIDList = array('0','3621', '3622');
$orderBy      = 'id_desc';

r($doc->getDocs($libIDlist[0], $moduleIDList[0], $orderBy))        && p('1:lib,title')  && e('1,文档标题1');//正常获取文档库下的文档
r($doc->getDocs($libIDlist[1], $moduleIDList, $orderBy))           && p('2:lib,module') && e('2,3622');     //获取文档目录下的文档
r(count($doc->getDocs($libIDlist[2], $moduleIDList[0], $orderBy))) && p()               && e('900');          //libID为空查询