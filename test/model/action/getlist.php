#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->getList();
cid=1
pid=1

测试获取objectType product objectId 1的动态信息 >> 1,admin,1
测试获取objectType story objectId 2的动态信息 >> 2,dev17,1
测试获取objectType productplan objectId 3的动态信息 >> 3,test18,1
测试获取objectType release objectId 4的动态信息 >> 4,admin,1
测试获取objectType project objectId 5的动态信息 >> 5,dev17,11
测试获取objectType task objectId 6的动态信息 >> 6,test18,1
测试获取objectType build objectId 7的动态信息 >> 7,admin,1
测试获取objectType bug objectId 8的动态信息 >> 8,dev17,1
测试获取objectType testcase objectId 9的动态信息 >> 9,test18,2
测试获取objectType case objectId 10的动态信息 >> 10,admin,1
测试获取objectType testtask objectId 11的动态信息 >> 11,dev17,1
测试获取objectType user objectId 12的动态信息 >> 12,test18,1
测试获取objectType doc objectId 13的动态信息 >> 13,admin,1
测试获取objectType doclib objectId 14的动态信息 >> 14,dev17,1
测试获取objectType todo objectId 15的动态信息 >> 15,test18,1
测试获取objectType branch objectId 16的动态信息 >> 16,admin,1
测试获取objectType module objectId 17的动态信息 >> 17,dev17,1
测试获取objectType testsuite objectId 18的动态信息 >> 18,test18,a
测试获取objectType caselib objectId 19的动态信息 >> 19,admin,1
测试获取objectType testreport objectId 20的动态信息 >> 20,dev17,1
测试获取objectType entry objectId 21的动态信息 >> 21,test18,1
测试获取objectType webhook objectId 22的动态信息 >> 22,admin,a
测试获取objectType review objectId 23的动态信息 >> 23,dev17,1
测试获取objectType product objectId 24的动态信息 >> 24,test18,1
测试获取objectType story objectId 25的动态信息 >> 25,admin,1
测试获取objectType productplan objectId 26的动态信息 >> 26,dev17,1
测试获取objectType release objectId 27的动态信息 >> 27,test18,1
测试获取objectType project objectId 28的动态信息 >> 28,admin,33
测试获取objectType task objectId 29的动态信息 >> 29,dev17,1
测试获取objectType build objectId 30的动态信息 >> 30,test18,1
测试获取objectType bug objectId 31的动态信息 >> 31,admin,1
测试获取objectType testcase objectId 32的动态信息 >> 32,项目经理17,1
测试获取objectType case objectId 33的动态信息 >> 33,test18,1
测试获取objectType testtask objectId 34的动态信息 >> 34,admin,1
测试获取objectType user objectId 35的动态信息 >> 35,dev17,1
测试获取objectType doc objectId 36的动态信息 >> 36,test18,1
测试获取objectType doclib objectId 37的动态信息 >> 37,admin,1
测试获取objectType todo objectId 38的动态信息 >> 38,dev17,1
测试获取objectType branch objectId 39的动态信息 >> 39,test18,a

*/

$objectType = array('product', 'story', 'productplan', 'release', 'project', 'task', 'build', 'bug', 'testcase', 'case', 'testtask', 'user', 'doc', 'doclib', 'todo', 'branch', 'module', 'testsuite', 'caselib', 'testreport', 'entry', 'webhook', 'review');
$objectId   = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39);

$action = new actionTest();

r($action->getListTest($objectType[0],  $objectId[0]))  && p('id,actor,extra') && e("1,admin,1");       // 测试获取objectType product objectId 1的动态信息
r($action->getListTest($objectType[1],  $objectId[1]))  && p('id,actor,extra') && e("2,dev17,1");       // 测试获取objectType story objectId 2的动态信息
r($action->getListTest($objectType[2],  $objectId[2]))  && p('id,actor,extra') && e("3,test18,1");      // 测试获取objectType productplan objectId 3的动态信息
r($action->getListTest($objectType[3],  $objectId[3]))  && p('id,actor,extra') && e("4,admin,1");       // 测试获取objectType release objectId 4的动态信息
r($action->getListTest($objectType[4],  $objectId[4]))  && p('id,actor,extra') && e("5,dev17,11");      // 测试获取objectType project objectId 5的动态信息
r($action->getListTest($objectType[5],  $objectId[5]))  && p('id,actor,extra') && e("6,test18,1");      // 测试获取objectType task objectId 6的动态信息
r($action->getListTest($objectType[6],  $objectId[6]))  && p('id,actor,extra') && e("7,admin,1");       // 测试获取objectType build objectId 7的动态信息
r($action->getListTest($objectType[7],  $objectId[7]))  && p('id,actor,extra') && e("8,dev17,1");       // 测试获取objectType bug objectId 8的动态信息
r($action->getListTest($objectType[8],  $objectId[8]))  && p('id,actor,extra') && e("9,test18,2");      // 测试获取objectType testcase objectId 9的动态信息
r($action->getListTest($objectType[9],  $objectId[9]))  && p('id,actor,extra') && e("10,admin,1");      // 测试获取objectType case objectId 10的动态信息
r($action->getListTest($objectType[10], $objectId[10])) && p('id,actor,extra') && e("11,dev17,1");      // 测试获取objectType testtask objectId 11的动态信息
r($action->getListTest($objectType[11], $objectId[11])) && p('id,actor,extra') && e("12,test18,1");     // 测试获取objectType user objectId 12的动态信息
r($action->getListTest($objectType[12], $objectId[12])) && p('id,actor,extra') && e("13,admin,1");      // 测试获取objectType doc objectId 13的动态信息
r($action->getListTest($objectType[13], $objectId[13])) && p('id,actor,extra') && e("14,dev17,1");      // 测试获取objectType doclib objectId 14的动态信息
r($action->getListTest($objectType[14], $objectId[14])) && p('id,actor,extra') && e("15,test18,1");     // 测试获取objectType todo objectId 15的动态信息
r($action->getListTest($objectType[15], $objectId[15])) && p('id,actor,extra') && e("16,admin,1");      // 测试获取objectType branch objectId 16的动态信息
r($action->getListTest($objectType[16], $objectId[16])) && p('id,actor,extra') && e("17,dev17,1");      // 测试获取objectType module objectId 17的动态信息
r($action->getListTest($objectType[17], $objectId[17])) && p('id,actor,extra') && e("18,test18,a");     // 测试获取objectType testsuite objectId 18的动态信息
r($action->getListTest($objectType[18], $objectId[18])) && p('id,actor,extra') && e("19,admin,1");      // 测试获取objectType caselib objectId 19的动态信息
r($action->getListTest($objectType[19], $objectId[19])) && p('id,actor,extra') && e("20,dev17,1");      // 测试获取objectType testreport objectId 20的动态信息
r($action->getListTest($objectType[20], $objectId[20])) && p('id,actor,extra') && e("21,test18,1");     // 测试获取objectType entry objectId 21的动态信息
r($action->getListTest($objectType[21], $objectId[21])) && p('id,actor,extra') && e("22,admin,a");      // 测试获取objectType webhook objectId 22的动态信息
r($action->getListTest($objectType[22], $objectId[22])) && p('id,actor,extra') && e("23,dev17,1");      // 测试获取objectType review objectId 23的动态信息
r($action->getListTest($objectType[0],  $objectId[23])) && p('id,actor,extra') && e("24,test18,1");     // 测试获取objectType product objectId 24的动态信息
r($action->getListTest($objectType[1],  $objectId[24])) && p('id,actor,extra') && e("25,admin,1");      // 测试获取objectType story objectId 25的动态信息
r($action->getListTest($objectType[2],  $objectId[25])) && p('id,actor,extra') && e("26,dev17,1");      // 测试获取objectType productplan objectId 26的动态信息
r($action->getListTest($objectType[3],  $objectId[26])) && p('id,actor,extra') && e("27,test18,1");     // 测试获取objectType release objectId 27的动态信息
r($action->getListTest($objectType[4],  $objectId[27])) && p('id,actor,extra') && e("28,admin,33");     // 测试获取objectType project objectId 28的动态信息
r($action->getListTest($objectType[5],  $objectId[28])) && p('id,actor,extra') && e("29,dev17,1");      // 测试获取objectType task objectId 29的动态信息
r($action->getListTest($objectType[6],  $objectId[29])) && p('id,actor,extra') && e("30,test18,1");     // 测试获取objectType build objectId 30的动态信息
r($action->getListTest($objectType[7],  $objectId[30])) && p('id,actor,extra') && e("31,admin,1");      // 测试获取objectType bug objectId 31的动态信息
r($action->getListTest($objectType[8],  $objectId[31])) && p('id,actor,extra') && e("32,项目经理17,1"); // 测试获取objectType testcase objectId 32的动态信息
r($action->getListTest($objectType[9],  $objectId[32])) && p('id,actor,extra') && e("33,test18,1");     // 测试获取objectType case objectId 33的动态信息
r($action->getListTest($objectType[10], $objectId[33])) && p('id,actor,extra') && e("34,admin,1");      // 测试获取objectType testtask objectId 34的动态信息
r($action->getListTest($objectType[11], $objectId[34])) && p('id,actor,extra') && e("35,dev17,1");      // 测试获取objectType user objectId 35的动态信息
r($action->getListTest($objectType[12], $objectId[35])) && p('id,actor,extra') && e("36,test18,1");     // 测试获取objectType doc objectId 36的动态信息
r($action->getListTest($objectType[13], $objectId[36])) && p('id,actor,extra') && e("37,admin,1");      // 测试获取objectType doclib objectId 37的动态信息
r($action->getListTest($objectType[14], $objectId[37])) && p('id,actor,extra') && e("38,dev17,1");      // 测试获取objectType todo objectId 38的动态信息
r($action->getListTest($objectType[15], $objectId[38])) && p('id,actor,extra') && e("39,test18,a");     // 测试获取objectType branch objectId 39的动态信息
