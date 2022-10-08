#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getByIdListTest();
cid=1
pid=1

敏捷项目查询 >> 迭代1
瀑布项目查询 >> stage
看板项目查询 >> project61

*/

$executionIDList = array('101', '131', '161');

$execution = new executionTest();
r($execution->getByIdListTest($executionIDList)) && p('101:name') && e('迭代1');     // 敏捷项目查询
r($execution->getByIdListTest($executionIDList)) && p('131:type') && e('stage');     // 瀑布项目查询
r($execution->getByIdListTest($executionIDList)) && p('161:code') && e('project61'); // 看板项目查询