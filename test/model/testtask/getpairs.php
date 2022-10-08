#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 testtaskModel->getPairs();
cid=1
pid=1



*/

global $tester;
$tester->loadModel('testtask');

$appendIdList = array(40, 45, 50);

$taskPairs1 = $tester->testtask->getPairs(1);
$taskPairs2 = $tester->testtask->getPairs(1, 101);
$taskPairs3 = $tester->testtask->getPairs(1, 101, $appendIdList);

r(count($taskPairs1)) && p() && e(''); // 获取产品1下的测试单的数量 
r(count($taskPairs2)) && p() && e(''); // 获取产品1下的测试单的数量
r(count($taskPairs3)) && p() && e(''); // 获取产品1下的测试单的数量
r($taskPairs1)        && p() && e(''); // 获取产品1下的测试单的数量
r($taskPairs2)        && p() && e(''); // 获取产品1下的测试单的数量
r($taskPairs3)        && p() && e(''); // 获取产品1下的测试单的数量