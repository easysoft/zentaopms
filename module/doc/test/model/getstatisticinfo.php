#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';
su('admin');

/**

title=测试 docModel->getStatisticInfo();
cid=1
pid=1

测试正常查询 >> 900,0,0,900,0,900,900

*/

$doc = new docTest();

r($doc->getStatisticInfoTest()) && p('totalDocs,todayEditedDocs,lastEditedDocs,lastAddedDocs,myCollection,myDocs,pastEditedDocs') && e('900,0,0,900,0,900,900'); //测试正常查询