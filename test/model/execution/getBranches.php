#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getBranchesTest();
cid=1
pid=1

敏捷项目下根据执行查询产品分支 >> 0,101,102
瀑布项目下根据执行查询产品分支 >> 0,11,12
看板项目下根据执行查询产品分支 >> 0,33,34
非分支产品执行查询产品分支 >> 0
敏捷执行关联产品分支统计 >> 3
瀑布执行关联产品分支统计 >> 2
看板执行关联产品分支统计 >> 2
正常产品分支统计 >> 2

*/

$executionIDList = array('101','146', '617', '121');
$count         = array('0','1');

$execution = new executionTest();
//var_dump($execution->getBranchesTest($executionIDList[3],$count[0]));die;
r($execution->getBranchesTest($executionIDList[0],$count[0])) && p('91')  && e('0,101,102'); // 敏捷项目下根据执行查询产品分支
r($execution->getBranchesTest($executionIDList[1],$count[0])) && p('46')  && e('0,11,12');   // 瀑布项目下根据执行查询产品分支
r($execution->getBranchesTest($executionIDList[2],$count[0])) && p('57')  && e('0,33,34');   // 看板项目下根据执行查询产品分支
r($execution->getBranchesTest($executionIDList[3],$count[0])) && p('21')  && e('0');         // 非分支产品执行查询产品分支
r($execution->getBranchesTest($executionIDList[0],$count[1])) && p()      && e('3');         // 敏捷执行关联产品分支统计
r($execution->getBranchesTest($executionIDList[1],$count[1])) && p()      && e('2');         // 瀑布执行关联产品分支统计
r($execution->getBranchesTest($executionIDList[2],$count[1])) && p()      && e('2');         // 看板执行关联产品分支统计
r($execution->getBranchesTest($executionIDList[3],$count[1])) && p()      && e('2');         // 正常产品分支统计