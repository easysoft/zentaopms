#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getObjectsByDoc();
cid=1
pid=1



*/
global $tester;
$doc = $tester->loadModel('doc');

$docIdList = array('17', '117', '217');

r($doc->getObjectsByDoc($docIdList)) && p('0:27;1:127;2:17') && e('项目17;迭代27;正常产品17');//查询文档库的项目产品迭代