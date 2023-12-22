#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testreport.class.php';

zdTable('bug')->config('getbugs4test_bug')->gen(40);
zdTable('testtask')->gen(0);
zdTable('testtask')->gen(20);
zdTable('build')->gen(10);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 testreportModel->getBugs4Test();
cid=1
pid=1

*/
$buildIdList = array(array(1, 2, 3) ,array());
$product     = array(1, 0);
$taskID      = 1;
$type        = array('build', 'project', 'execution', '');

$testreport = new testreportTest();
r($testreport->getBugs4TestTest($buildIdList[0], $product[0], $taskID, $type[0])) && p() && e('1,2');   // 正常查询
r($testreport->getBugs4TestTest($buildIdList[1], $product[0], $taskID, $type[0])) && p() && e('0');     // buildIdList 为空查询
r($testreport->getBugs4TestTest($buildIdList[0], $product[1], $taskID, $type[0])) && p() && e('0');     // product为空查询
r($testreport->getBugs4TestTest($buildIdList[0], $product[0], $taskID, $type[1])) && p() && e('1,2');   // type为project查询
r($testreport->getBugs4TestTest($buildIdList[0], $product[0], $taskID, $type[2])) && p() && e('1,2');   // type为execution查询
r($testreport->getBugs4TestTest($buildIdList[0], $product[0], $taskID, $type[3])) && p() && e('1,2,3'); // type为空查询
r($testreport->getBugs4TestTest($buildIdList[1], $product[0], $taskID, $type[1])) && p() && e('0');     // buildIdList 为空 type为project查询
r($testreport->getBugs4TestTest($buildIdList[1], $product[0], $taskID, $type[2])) && p() && e('0');     // buildIdList 为空 type为execution查询
r($testreport->getBugs4TestTest($buildIdList[1], $product[0], $taskID, $type[3])) && p() && e('1,2,3'); // buildIdList 为空 type为空查询
