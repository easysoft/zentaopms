#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

zdTable('userquery')->gen(6);

/**

title=测试 searchModel->deleteQuery();
timeout=0
cid=1

- 测试删除ID为1的数据后剩余的数量 @5
- 测试删除ID为2的数据后剩余的数量 @4

*/

$search = new searchTest();

$queryIDList = array('1', '2');

r($search->deleteQueryTest($queryIDList[0])) && p() && e('5'); //测试删除ID为1的数据后剩余的数量
r($search->deleteQueryTest($queryIDList[1])) && p() && e('4'); //测试删除ID为2的数据后剩余的数量