#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getExecution();
cid=1
pid=1

检查获取数据的id >> 3
检查获取数据的PM >> admin
检查获取数据的burns >> 35

*/

$tutorial = new tutorialTest();

r($tutorial->getExecutionTest()) && p('id')      && e('3');     //检查获取数据的id
r($tutorial->getExecutionTest()) && p('PM')      && e('admin'); //检查获取数据的PM
r($tutorial->getExecutionTest()) && p('burns:0') && e('35');    //检查获取数据的burns