#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/search.class.php';
su('admin');

/**

title=测试 searchModel->getQuery();
cid=1
pid=1

查询ID为1的搜索条件名称及查询数量 >> 任务查询测试条件,27
查询ID为2的搜索条件名称及查询数量 >> 需求查找条件,1
查询ID为3的搜索条件名称及查询数量 >> 用户测试条件,1
查询ID为4的搜索条件名称及查询数量 >> 项目版本搜索,1
查询ID为5的搜索条件名称及查询数量 >> 执行版本搜索,1
查询ID为6的搜索条件名称及查询数量 >> 设计搜索,32

*/

$search = new searchTest();

$queryIDList = array('1', '2', '3', '4', '5', '6');

r($search->getQueryTest($queryIDList[0])) && p('title,queryCount') && e('任务查询测试条件,27'); //查询ID为1的搜索条件名称及查询数量
r($search->getQueryTest($queryIDList[1])) && p('title,queryCount') && e('需求查找条件,1');      //查询ID为2的搜索条件名称及查询数量
r($search->getQueryTest($queryIDList[2])) && p('title,queryCount') && e('用户测试条件,1');      //查询ID为3的搜索条件名称及查询数量
r($search->getQueryTest($queryIDList[3])) && p('title,queryCount') && e('项目版本搜索,1');      //查询ID为4的搜索条件名称及查询数量
r($search->getQueryTest($queryIDList[4])) && p('title,queryCount') && e('执行版本搜索,1');      //查询ID为5的搜索条件名称及查询数量
r($search->getQueryTest($queryIDList[5])) && p('title,queryCount') && e('设计搜索,32');         //查询ID为6的搜索条件名称及查询数量