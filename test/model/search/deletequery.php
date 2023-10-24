#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/search.class.php';
su('admin');

/**

title=测试 searchModel->deleteQuery();
cid=1
pid=1

测试删除ID为1的数据后剩余的数量 >> 5
测试删除ID为2的数据后剩余的数量 >> 4

*/

$search = new searchTest();

$queryIDList = array('1', '2');

r($search->deleteQueryTest($queryIDList[0])) && p() && e('5'); //测试删除ID为1的数据后剩余的数量
r($search->deleteQueryTest($queryIDList[1])) && p() && e('4'); //测试删除ID为2的数据后剩余的数量

