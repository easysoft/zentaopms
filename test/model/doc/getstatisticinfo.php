#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getStatisticInfo();
cid=1
pid=1

测试正常查询 >> 900,0,0,900,0,900,900

*/

$doc = new docTest();

r($doc->getStatisticInfoTest()) && p('totalDocs,todayEditedDocs,lastEditedDocs,lastAddedDocs,myCollection,myDocs,pastEditedDocs') && e('900,0,0,900,0,900,900'); //测试正常查询