#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('bug')->gen(20);
zenData('build')->gen(100);
zenData('project')->loadYaml('execution2')->gen(90);

/**

title=bugModel->getProductLeftBugs();
timeout=0
cid=15390

- 测试获取buildID为11 productID为1的bug @BUG1,BUG2,BUG3


- 测试获取buildID为12 productID为2的bug @BUG4,BUG5,BUG6


- 测试获取buildID为14 productID为4的bug @BUG10,BUG11,BUG12


- 测试获取buildID为16 productID为6的bug @bug16,BUG17,BUG18


- 测试获取buildID为13 productID为3的bug @缺陷!()(){}|+=^&*#测试bug名称到底可以有多长！#￥&*":.<>。?/（）;7,bug8,BUG9


- 测试获取buildID为15 productID为5的bug @BUG13,BUG14,缺陷!()(){}|+=^&*#测试bug名称到底可以有多长！#￥&*":.<>。?/（）;15


- 测试获取不存在的build的bug @0

- 测试获取不存在的product的bug @0

*/

$buildIDList   = array('11', '12', '13', '14', '15', '16', '1000001');
$productIDList = array('1', '2', '3', '4', '5', '6', '1000001');
$branch        = array('', 2);
$linkedBugs    = '2';

$bug=new bugTest();
r($bug->getProductLeftBugsTest(array($buildIDList[0]), $productIDList[0])) && p() && e('BUG1,BUG2,BUG3');    // 测试获取buildID为11 productID为1的bug
r($bug->getProductLeftBugsTest(array($buildIDList[1]), $productIDList[1])) && p() && e('BUG4,BUG5,BUG6');    // 测试获取buildID为12 productID为2的bug
r($bug->getProductLeftBugsTest(array($buildIDList[3]), $productIDList[3])) && p() && e('BUG10,BUG11,BUG12'); // 测试获取buildID为14 productID为4的bug
r($bug->getProductLeftBugsTest(array($buildIDList[5]), $productIDList[5])) && p() && e('bug16,BUG17,BUG18'); // 测试获取buildID为16 productID为6的bug
r($bug->getProductLeftBugsTest(array($buildIDList[2]), $productIDList[2])) && p() && e('缺陷!()(){}|+=^&*#测试bug名称到底可以有多长！#￥&*":.<>。?/（）;7,bug8,BUG9');    // 测试获取buildID为13 productID为3的bug
r($bug->getProductLeftBugsTest(array($buildIDList[4]), $productIDList[4])) && p() && e('BUG13,BUG14,缺陷!()(){}|+=^&*#测试bug名称到底可以有多长！#￥&*":.<>。?/（）;15'); // 测试获取buildID为15 productID为5的bug
r($bug->getProductLeftBugsTest(array($buildIDList[6]), $productIDList[1])) && p() && e('0'); // 测试获取不存在的build的bug
r($bug->getProductLeftBugsTest(array($buildIDList[1]), $productIDList[6])) && p() && e('0'); // 测试获取不存在的product的bug
r($bug->getProductLeftBugsTest(array($buildIDList[0]), $productIDList[0], $branch[1])) && p() && e('BUG1,BUG2,BUG3'); // 测试获取buildID为11 productID为1 分支1的bug
r($bug->getProductLeftBugsTest(array($buildIDList[0]), $productIDList[0], $branch[0], $linkedBugs)) && p() && e('BUG1,BUG3');    // 测试获取buildID为11 productID为1 已关联bug为2 的bug
r($bug->getProductLeftBugsTest(array($buildIDList[0]), $productIDList[0], $branch[1], $linkedBugs)) && p() && e('BUG1,BUG3');    // 测试获取buildID为11 productID为1 分支1 已关联bug为2 的bug
