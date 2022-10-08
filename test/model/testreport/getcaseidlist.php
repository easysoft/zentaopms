#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testreport.class.php';
su('admin');

/**

title=测试 testreportModel->getCaseIdList();
cid=1
pid=1

正常查询 >> 1,4
查询创建者不为自己的数据 >> 5,8
查询reportID为空的数据 >> 0

*/
$reportID = array('1', '2', '');

$testreport = new testreportTest();

r($testreport->getCaseIdListTest($reportID[0])) && p('1,4') && e('1,4'); //正常查询
r($testreport->getCaseIdListTest($reportID[1])) && p('5,8') && e('5,8'); //查询创建者不为自己的数据
r($testreport->getCaseIdListTest($reportID[2])) && p()      && e('0');   //查询reportID为空的数据