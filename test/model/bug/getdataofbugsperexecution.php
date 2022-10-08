#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getDataOfBugsPerExecution();
cid=1
pid=1

获取执行101数据 >> 迭代1,4,迭代1
获取执行102数据 >> 迭代2,4,迭代2
获取执行103数据 >> 迭代3,4,迭代3
获取执行131数据 >> 阶段31,3,阶段31
获取执行132数据 >> 阶段32,3,阶段32
获取执行133数据 >> 阶段33,3,阶段33
获取执行161数据 >> 看板61,3,看板61
获取执行162数据 >> 看板62,3,看板62
获取执行163数据 >> 看板63,3,看板63

*/

$bug=new bugTest();
r($bug->getDataOfBugsPerExecutionTest()) && p('101:name,value,title') && e('迭代1,4,迭代1');   // 获取执行101数据
r($bug->getDataOfBugsPerExecutionTest()) && p('102:name,value,title') && e('迭代2,4,迭代2');   // 获取执行102数据
r($bug->getDataOfBugsPerExecutionTest()) && p('103:name,value,title') && e('迭代3,4,迭代3');   // 获取执行103数据
r($bug->getDataOfBugsPerExecutionTest()) && p('131:name,value,title') && e('阶段31,3,阶段31'); // 获取执行131数据
r($bug->getDataOfBugsPerExecutionTest()) && p('132:name,value,title') && e('阶段32,3,阶段32'); // 获取执行132数据
r($bug->getDataOfBugsPerExecutionTest()) && p('133:name,value,title') && e('阶段33,3,阶段33'); // 获取执行133数据
r($bug->getDataOfBugsPerExecutionTest()) && p('161:name,value,title') && e('看板61,3,看板61'); // 获取执行161数据
r($bug->getDataOfBugsPerExecutionTest()) && p('162:name,value,title') && e('看板62,3,看板62'); // 获取执行162数据
r($bug->getDataOfBugsPerExecutionTest()) && p('163:name,value,title') && e('看板63,3,看板63'); // 获取执行163数据