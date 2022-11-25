#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 testtaskModel->getByUser();
cid=1
pid=1

获取用户user3的测试单数量 >> 1
获取用户user4的测试单数量 >> 1
获取用户user5的测试单数量 >> 1
获取用户user6的测试单数量 >> 1
获取用户user3的测试单详细信息 >> 测试单1,,wait,2022-04-08,2022-04-15
获取用户user4的测试单详细信息 >> 测试单2,,doing,2022-04-08,2022-04-15
获取用户user5的测试单详细信息 >> 测试单3,,done,2022-04-08,2022-04-15
获取用户user6的测试单详细信息 >> 测试单4,,blocked,2022-04-08,2022-04-15

*/

global $tester;
$tester->loadModel('testtask');

r(count($tester->testtask->getByUser('user3'))) && p()                               && e('1'); // 获取用户user3的测试单数量
r(count($tester->testtask->getByUser('user4'))) && p()                               && e('1'); // 获取用户user4的测试单数量
r(count($tester->testtask->getByUser('user5'))) && p()                               && e('1'); // 获取用户user5的测试单数量
r(count($tester->testtask->getByUser('user6'))) && p()                               && e('1'); // 获取用户user6的测试单数量
r($tester->testtask->getByUser('user3'))        && p('0:name,type,status,begin,end') && e('测试单1,,wait,2022-04-08,2022-04-15');    // 获取用户user3的测试单详细信息
r($tester->testtask->getByUser('user4'))        && p('0:name,type,status,begin,end') && e('测试单2,,doing,2022-04-08,2022-04-15');   // 获取用户user4的测试单详细信息
r($tester->testtask->getByUser('user5'))        && p('0:name,type,status,begin,end') && e('测试单3,,done,2022-04-08,2022-04-15');    // 获取用户user5的测试单详细信息
r($tester->testtask->getByUser('user6'))        && p('0:name,type,status,begin,end') && e('测试单4,,blocked,2022-04-08,2022-04-15'); // 获取用户user6的测试单详细信息