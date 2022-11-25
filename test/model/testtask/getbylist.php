#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 testtaskModel->getByList();
cid=1
pid=1

查看按照ID列表获取的测试单的数量 >> 5
查看按照ID列表获取的测试单的详情 >> 测试单1,,wait,2022-04-08,2022-04-15

*/

global $tester;
$tester->loadModel('testtask');

$taskIdList = array(1, 2, 3, 4, 5);

r(count($tester->testtask->getByList($taskIdList))) && p()                               && e('5');                                   // 查看按照ID列表获取的测试单的数量
r($tester->testtask->getByList($taskIdList))        && p('1:name,type,status,begin,end') && e('测试单1,,wait,2022-04-08,2022-04-15'); // 查看按照ID列表获取的测试单的详情