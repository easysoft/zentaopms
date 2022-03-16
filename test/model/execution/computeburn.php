#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->computeBurnTest();
cid=1
pid=1

正常初始化 >> project600
初始化后数据统计 >> 516

*/

$count = array('0','1');

$execution = new executionTest();
r($execution->computeBurnTest($count[0])) && p('700:executionName') && e('project600'); // 正常初始化
r($execution->computeBurnTest($count[1])) && p()                    && e('516');        // 初始化后数据统计