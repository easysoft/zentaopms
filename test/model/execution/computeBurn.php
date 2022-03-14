#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->computeBurnTest();
cid=1
pid=1

敏捷执行关联用例 >> 101,1,1
瀑布执行关联用例 >> 131,43,169
看板执行关联用例 >> 161,68,269
敏捷执行关联用例统计 >> 4
瀑布执行关联用例统计 >> 4
看板执行关联用例统计 >> 4

*/

$count = array('0','1');

$execution = new executionTest();
r($execution->computeBurnTest($count[0])) && p('700:executionName') && e('project600'); // 正常初始化
r($execution->computeBurnTest($count[1])) && p()                    && e('516');        // 初始化后数据统计
