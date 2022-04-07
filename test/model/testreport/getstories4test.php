#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testreport.class.php';
su('admin');

/**

title=测试 testreportModel->getStories4Test();
cid=1
pid=1

buildIdList不为空查询 >> 0
buildIdList为空查询 >> 0

*/
$buildIdList = array('11', '');

$testreport = new testreportTest();

r($testreport->getStories4TestTest($buildIdList[0])) && p() && e('0'); //buildIdList不为空查询
r($testreport->getStories4TestTest($buildIdList[1])) && p() && e('0'); //buildIdList为空查询