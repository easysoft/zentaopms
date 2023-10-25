#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

zdTable('userquery')->gen(30);

/**

title=测试 searchModel->getQuery();
cid=1
pid=1

查询ID为1的搜索条件名称及查询数量 >> 这是搜索条件名称1,0
查询ID为2的搜索条件名称及查询数量 >> 这是搜索条件名称2,0
查询ID为3的搜索条件名称及查询数量 >> 这是搜索条件名称3,0
查询ID为4的搜索条件名称及查询数量 >> 这是搜索条件名称4,0
查询ID为5的搜索条件名称及查询数量 >> 这是搜索条件名称5,0
查询ID为6的搜索条件名称及查询数量 >> 这是搜索条件名称6,0

*/

$search = new searchTest();

$queryIDList = array('1', '2', '3', '4', '5', '6');

r($search->getQueryTest($queryIDList[0])) && p('title,queryCount') && e('这是搜索条件名称1,0');      //查询ID为1的搜索条件名称及查询数量
r($search->getQueryTest($queryIDList[1])) && p('title,queryCount') && e('这是搜索条件名称2,0');      //查询ID为2的搜索条件名称及查询数量
r($search->getQueryTest($queryIDList[2])) && p('title,queryCount') && e('这是搜索条件名称3,0');      //查询ID为3的搜索条件名称及查询数量
r($search->getQueryTest($queryIDList[3])) && p('title,queryCount') && e('这是搜索条件名称4,0');      //查询ID为4的搜索条件名称及查询数量
r($search->getQueryTest($queryIDList[4])) && p('title,queryCount') && e('这是搜索条件名称5,0');      //查询ID为5的搜索条件名称及查询数量
r($search->getQueryTest($queryIDList[5])) && p('title,queryCount') && e('这是搜索条件名称6,0');      //查询ID为6的搜索条件名称及查询数量
