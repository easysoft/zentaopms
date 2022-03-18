#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getRelatedExecutionsTest();
cid=1
pid=1

查询敏捷执行关联查询 >> 项目1
查询瀑布执行关联查询 >> 项目36
查询看板执行关联查询 >> 项目57
查询敏捷执行关联统计 >> 22
查询瀑布执行关联统计 >> 26
查询看板执行关联统计 >> 22

*/

$executionIDList = array('101','146', '617');
$count           = array('0','1');

$execution = new executionTest();
//var_dump($execution->getRelatedExecutionsTest($executionIDList[2],$count[0]));die;
r($execution->getRelatedExecutionsTest($executionIDList[0],$count[0])) && p('11')  && e('项目1');  // 查询敏捷执行关联查询
r($execution->getRelatedExecutionsTest($executionIDList[1],$count[0])) && p('46')  && e('项目36'); // 查询瀑布执行关联查询
r($execution->getRelatedExecutionsTest($executionIDList[2],$count[0])) && p('67')  && e('项目57'); // 查询看板执行关联查询
r($execution->getRelatedExecutionsTest($executionIDList[0],$count[1])) && p()      && e('22');     // 查询敏捷执行关联统计
r($execution->getRelatedExecutionsTest($executionIDList[1],$count[1])) && p()      && e('26');     // 查询瀑布执行关联统计
r($execution->getRelatedExecutionsTest($executionIDList[2],$count[1])) && p()      && e('22');     // 查询看板执行关联统计