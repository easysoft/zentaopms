#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->getById();
cid=1
pid=1

测试获取case 1 的信息 >> 这个是测试用例1,feature,wait
测试获取case 2 的信息 >> 这个是测试用例2,performance,normal
测试获取case 3 的信息 >> 这个是测试用例3,config,blocked
测试获取case 4 的信息 >> 这个是测试用例4,install,investigate
测试获取case 5 的信息 >> 这个是测试用例5,security,wait

*/

$caseIDList = array(1, 2, 3, 4, 5);

$testcase = new testcaseTest();

r($testcase->getByIdTest($caseIDList[0])) && p('title,type,status') && e('这个是测试用例1,feature,wait');        // 测试获取case 1 的信息
r($testcase->getByIdTest($caseIDList[1])) && p('title,type,status') && e('这个是测试用例2,performance,normal');  // 测试获取case 2 的信息
r($testcase->getByIdTest($caseIDList[2])) && p('title,type,status') && e('这个是测试用例3,config,blocked');      // 测试获取case 3 的信息
r($testcase->getByIdTest($caseIDList[3])) && p('title,type,status') && e('这个是测试用例4,install,investigate'); // 测试获取case 4 的信息
r($testcase->getByIdTest($caseIDList[4])) && p('title,type,status') && e('这个是测试用例5,security,wait');       // 测试获取case 5 的信息