#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getPrivLibsByDoc();
cid=1
pid=1

正常查询 >> 900
正常查询统计 >> 600

*/
global $tester;
$doc = $tester->loadModel('doc');

r($doc->getPrivLibsByDoc())        && p('900') && e('900');//正常查询
r(count($doc->getPrivLibsByDoc())) && p()      && e('600');//正常查询统计