#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->getByOpenedBy();
cid=1
pid=1

测试查询由 test1 创建的case信息 >> 这个是测试用例1;这个是测试用例101;这个是测试用例201;这个是测试用例301
测试查询由 test2 创建的case信息 >> 这个是测试用例2;这个是测试用例102;这个是测试用例202;这个是测试用例302
测试查询由 test3 创建的case信息 >> 这个是测试用例3;这个是测试用例103;这个是测试用例203;这个是测试用例303
测试查询由 test4 创建的case信息 >> 这个是测试用例4;这个是测试用例104;这个是测试用例204;这个是测试用例304
测试查询由 test5 创建的case信息 >> 这个是测试用例5;这个是测试用例105;这个是测试用例205;这个是测试用例305

*/
$accountList = array('test1', 'test2', 'test3', 'test4', 'test5');

$testcase = new testcaseTest();

r($testcase->getByOpenedByTest($accountList[0])) && p('1:title;101:title;201:title;301:title') && e('这个是测试用例1;这个是测试用例101;这个是测试用例201;这个是测试用例301'); // 测试查询由 test1 创建的case信息
r($testcase->getByOpenedByTest($accountList[1])) && p('2:title;102:title;202:title;302:title') && e('这个是测试用例2;这个是测试用例102;这个是测试用例202;这个是测试用例302'); // 测试查询由 test2 创建的case信息
r($testcase->getByOpenedByTest($accountList[2])) && p('3:title;103:title;203:title;303:title') && e('这个是测试用例3;这个是测试用例103;这个是测试用例203;这个是测试用例303'); // 测试查询由 test3 创建的case信息
r($testcase->getByOpenedByTest($accountList[3])) && p('4:title;104:title;204:title;304:title') && e('这个是测试用例4;这个是测试用例104;这个是测试用例204;这个是测试用例304'); // 测试查询由 test4 创建的case信息
r($testcase->getByOpenedByTest($accountList[4])) && p('5:title;105:title;205:title;305:title') && e('这个是测试用例5;这个是测试用例105;这个是测试用例205;这个是测试用例305'); // 测试查询由 test5 创建的case信息