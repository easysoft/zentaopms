#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->processPlans();
cid=1
pid=1

测试获取计划 11 12的信息 >> 项目1,正常产品1;项目2,正常产品2
测试获取计划 13 14的信息 >> 项目3,正常产品3;项目4,正常产品4
测试获取计划 15 16 17的信息 >> 项目5,正常产品5;项目6,正常产品6;项目7,正常产品7
测试获取计划 18 19 20的信息 >> 项目8,正常产品8;项目9,正常产品9;项目10,正常产品10
测试获取计划 21 22 23 24的信息 >> 项目11,正常产品1;项目12,正常产品2;项目13,正常产品3;项目14,正常产品4

*/
$planIDList = array(array(11, 12), array(13, 14), array(15, 16, 17), array(18, 19, 20), array(21, 22, 23, 24));

$programplan = new programplanTest();

r($programplan->processPlansTest($planIDList[0])) && p('11:name,productName;12:name,productName')                                         && e('项目1,正常产品1;项目2,正常产品2');                                     // 测试获取计划 11 12的信息
r($programplan->processPlansTest($planIDList[1])) && p('13:name,productName;14:name,productName')                                         && e('项目3,正常产品3;项目4,正常产品4');                                     // 测试获取计划 13 14的信息
r($programplan->processPlansTest($planIDList[2])) && p('15:name,productName;16:name,productName;17:name,productName')                     && e('项目5,正常产品5;项目6,正常产品6;项目7,正常产品7');                     // 测试获取计划 15 16 17的信息
r($programplan->processPlansTest($planIDList[3])) && p('18:name,productName;19:name,productName;20:name,productName')                     && e('项目8,正常产品8;项目9,正常产品9;项目10,正常产品10');                   // 测试获取计划 18 19 20的信息
r($programplan->processPlansTest($planIDList[4])) && p('21:name,productName;22:name,productName;23:name,productName;24:name,productName') && e('项目11,正常产品1;项目12,正常产品2;项目13,正常产品3;项目14,正常产品4'); // 测试获取计划 21 22 23 24的信息