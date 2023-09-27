#!/usr/bin/env php
<?php
/**

title=测试 testtaskModel->getByBuild();
timeout=0
cid=1

 */
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('testtask')->gen(5);
su('admin');

global $tester;
$tester->loadModel('testtask');

r($tester->testtask->getByID(0)) && p('') && e('0'); // 获取ID为0的测试单，返回空
r($tester->testtask->getByID(1)) && p('project,name,product,productName,productType,execution,executionName,branch,build,buildName,owner,desc,status,auto') && e('11,测试单1,1,正常产品1,normal,101,迭代5,0,11,执行版本版本11,user3,这是测试单描述1,wait,no'); // 获取ID为1的测试单的详细信息
r($tester->testtask->getByID(2)) && p('project,name,product,productName,productType,execution,executionName,branch,build,buildName,owner,desc,status,auto') && e('12,测试单2,2,正常产品2,normal,102,迭代6,0,12,执行版本版本12,user4,这是测试单描述2,doing,no'); // 获取ID为2的测试单的详细信息
r($tester->testtask->getByID(3)) && p('project,name,product,productName,productType,execution,executionName,branch,build,buildName,owner,desc,status,auto') && e('13,测试单3,3,正常产品3,normal,103,迭代7,0,13,执行版本版本13,user5,这是测试单描述3,done,no'); // 获取ID为3的测试单的详细信息
