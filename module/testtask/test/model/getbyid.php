#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('product')->gen(100);
zdTable('project')->gen(100);
zdTable('project')->config('execution')->gen(300, false);
zdTable('projectproduct')->gen(100);
zdTable('build')->gen(500);
zdTable('testtask')->gen(500);

/**

title=测试 testtaskModel->getById();
timeout=0
cid=1

- 获取ID为0的测试单，返回空 @0

- 获取ID为1的测试单的详细信息
 - 属性project @11
 - 属性name @测试单1
 - 属性product @1
 - 属性productName @正常产品1
 - 属性productType @normal
 - 属性execution @101
 - 属性executionName @迭代1
 - 属性branch @0
 - 属性build @11
 - 属性buildName @执行版本版本11
 - 属性owner @user3
 - 属性desc @这是测试单描述1
 - 属性status @wait
 - 属性auto @no

- 获取ID为2的测试单的详细信息
 - 属性project @12
 - 属性name @测试单2
 - 属性product @2
 - 属性productName @正常产品2
 - 属性productType @normal
 - 属性execution @102
 - 属性executionName @迭代2
 - 属性branch @0
 - 属性build @12
 - 属性buildName @执行版本版本12
 - 属性owner @user4
 - 属性desc @这是测试单描述2
 - 属性status @doing
 - 属性auto @no

- 获取ID为3的测试单的详细信息
 - 属性project @13
 - 属性name @测试单3
 - 属性product @3
 - 属性productName @正常产品3
 - 属性productType @normal
 - 属性execution @103
 - 属性executionName @迭代3
 - 属性branch @0
 - 属性build @13
 - 属性buildName @执行版本版本13
 - 属性owner @user5
 - 属性desc @这是测试单描述3
 - 属性status @done
 - 属性auto @no

*/

global $tester;
$tester->loadModel('testtask');

r($tester->testtask->getByID(0)) && p('') && e('0'); // 获取ID为0的测试单，返回空
r($tester->testtask->getByID(1)) && p('project,name,product,productName,productType,execution,executionName,branch,build,buildName,owner,desc,status,auto') && e('11,测试单1,1,正常产品1,normal,101,迭代1,0,11,执行101版本11,user3,这是测试单描述1,wait,no'); // 获取ID为1的测试单的详细信息
r($tester->testtask->getByID(2)) && p('project,name,product,productName,productType,execution,executionName,branch,build,buildName,owner,desc,status,auto') && e('12,测试单2,2,正常产品2,normal,102,迭代2,0,12,执行102版本12,user4,这是测试单描述2,doing,no'); // 获取ID为2的测试单的详细信息
r($tester->testtask->getByID(3)) && p('project,name,product,productName,productType,execution,executionName,branch,build,buildName,owner,desc,status,auto') && e('13,测试单3,3,正常产品3,normal,103,迭代3,0,13,执行103版本13,user5,这是测试单描述3,done,no'); // 获取ID为3的测试单的详细信息
