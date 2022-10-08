#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testreport.class.php';
su('admin');

/**

title=测试 testreportModel->getById();
cid=1
pid=1

正常查询 >> 1,user3,1,2,3,4
reportID为空查询 >> 0
reportID不存在查询 >> 0

*/
$reportID = array('1', '', '16');

$testreport = new testreportTest();

r($testreport->getByIdTest($reportID[0])) && p('id,owner,cases') && e('1,user3,1,2,3,4');//正常查询
r($testreport->getByIdTest($reportID[1])) && p()           && e('0');                    //reportID为空查询
r($testreport->getByIdTest($reportID[2])) && p()           && e('0');                    //reportID不存在查询