#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testreport.class.php';
su('admin');

/**

title=测试 testreportModel->getBugs4Test();
cid=1
pid=1

正常查询 >> 0
buildIdList为空查询 >> 0
product为空查询 >> 0
type为project查询 >> 0
type为execution查询 >> 0
type为空查询 >> 1;301

*/
$buildIdList = array('11' ,'');
$product     = array('1', '');
$taskID      = '1';
$type        = array('build', 'project', 'execution', '');

$testreport = new testreportTest();
r($testreport->getBugs4TestTest($buildIdList[0], $product[0], $taskID, $type[0])) && p() && e('0'); //正常查询
r($testreport->getBugs4TestTest($buildIdList[1], $product[0], $taskID, $type[0])) && p() && e('0'); //buildIdList为空查询
r($testreport->getBugs4TestTest($buildIdList[0], $product[1], $taskID, $type[0])) && p() && e('0'); //product为空查询
r($testreport->getBugs4TestTest($buildIdList[0], $product[0], $taskID, $type[1])) && p() && e('0'); //type为project查询
r($testreport->getBugs4TestTest($buildIdList[0], $product[0], $taskID, $type[2])) && p() && e('0'); //type为execution查询
r($testreport->getBugs4TestTest($buildIdList[0], $product[0], $taskID, $type[3])) && p('1:id;301:id') && e('1;301'); //type为空查询