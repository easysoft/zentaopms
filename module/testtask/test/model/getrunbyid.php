#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testtask.class.php';

zdTable('case')->gen(7);
zdTable('testrun')->gen(7);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 testtaskModel->getRunById();
cid=1
pid=1



*/

$runIdList = array(1, 2, 3, 4, 5);

$testtask = new testtaskTest();

r($testtask->getRunByIdTest($runIdList[0])) && p('id,task') && e('1,1'); // 查询run 1 的信息
r($testtask->getRunByIdTest($runIdList[1])) && p('id,task') && e('2,1'); // 查询run 2 的信息
r($testtask->getRunByIdTest($runIdList[2])) && p('id,task') && e('3,1'); // 查询run 3 的信息
r($testtask->getRunByIdTest($runIdList[3])) && p('id,task') && e('4,1'); // 查询run 4 的信息
r($testtask->getRunByIdTest($runIdList[4])) && p('id,task') && e('5,2'); // 查询run 5 的信息
