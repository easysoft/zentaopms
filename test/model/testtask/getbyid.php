#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 testtaskModel->getById();
cid=1
pid=1

获取ID为0的测试单，返回空 >> 0
获取ID为1的测试单的详细信息 >> 测试单1,执行版本版本11,这是测试单描述1,2022-04-08,2022-04-15
获取ID为2的测试单的详细信息 >> 测试单2,执行版本版本12,这是测试单描述2,2022-04-08,2022-04-15
获取ID为3的测试单的详细信息 >> 测试单3,执行版本版本13,这是测试单描述3,2022-04-08,2022-04-15

*/

global $tester;
$tester->loadModel('testtask');

r($tester->testtask->getById(0)) && p('')                              && e('0'); // 获取ID为0的测试单，返回空
r($tester->testtask->getById(1)) && p('name,buildName,desc,begin,end') && e('测试单1,执行版本版本11,这是测试单描述1,2022-04-08,2022-04-15'); // 获取ID为1的测试单的详细信息
r($tester->testtask->getById(2)) && p('name,buildName,desc,begin,end') && e('测试单2,执行版本版本12,这是测试单描述2,2022-04-08,2022-04-15'); // 获取ID为2的测试单的详细信息
r($tester->testtask->getById(3)) && p('name,buildName,desc,begin,end') && e('测试单3,执行版本版本13,这是测试单描述3,2022-04-08,2022-04-15'); // 获取ID为3的测试单的详细信息