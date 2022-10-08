#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/search.class.php';
su('admin');

/**

title=测试 searchModel->getQueryPairs();
cid=1
pid=1

查询module为task的键值对 >> 任务查询测试条件
查询module为executionStory的键值对 >> 需求查找条件
查询module为user的键值对 >> 用户测试条件
查询module为projectBuild的键值对 >> 项目版本搜索
查询module为executionBuild的键值对 >> 执行版本搜索
查询module为design的键值对 >> 设计搜索
查询module不存在的情况 >> 我的查询

*/

$search = new searchTest();

$queryIDList = array('task','executionStory','user','projectBuild','executionBuild','design','nullValue');

r($search->getQueryPairsTest($queryIDList[0])) && p('1') && e('任务查询测试条件'); //查询module为task的键值对
r($search->getQueryPairsTest($queryIDList[1])) && p('2') && e('需求查找条件');     //查询module为executionStory的键值对
r($search->getQueryPairsTest($queryIDList[2])) && p('3') && e('用户测试条件');     //查询module为user的键值对
r($search->getQueryPairsTest($queryIDList[3])) && p('4') && e('项目版本搜索');     //查询module为projectBuild的键值对
r($search->getQueryPairsTest($queryIDList[4])) && p('5') && e('执行版本搜索');     //查询module为executionBuild的键值对
r($search->getQueryPairsTest($queryIDList[5])) && p('6') && e('设计搜索');         //查询module为design的键值对
r($search->getQueryPairsTest($queryIDList[6])) && p('')  && e('我的查询');         //查询module不存在的情况