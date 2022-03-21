#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getProductLeftBugs();
cid=1
pid=1

测试获取buildID为11 productID为1的bug  >> 0
测试获取buildID为12 productID为41的bug >> 0
测试获取buildID为13 productID为1的bug  >> BUG1,BUG2,BUG3
测试获取buildID为14 productID为41的bug >> BUG121,BUG122,BUG123
测试获取buildID为15 productID为1的bug  >> BUG1,BUG2,BUG3
测试获取buildID为16 productID为1的bug  >> BUG1,BUG2,BUG3
测试获取不存在的build的bug             >> 0
测试获取不存在的product的bug           >> 0

*/


$buildIDList   = array('1', '2', '3', '4', '5', '6', '1000001');
$productIDList = array('1', '41', '1000001');

$bug=new bugTest();
r($bug->getProductLeftBugsTest($buildIDList[0], $productIDList[0])) && p() && e('0');                    // 测试获取buildID为11 productID为1的bug
r($bug->getProductLeftBugsTest($buildIDList[1], $productIDList[1])) && p() && e('0');                    // 测试获取buildID为12 productID为41的bug
r($bug->getProductLeftBugsTest($buildIDList[2], $productIDList[0])) && p() && e('BUG1,BUG2,BUG3');       // 测试获取buildID为13 productID为1的bug
r($bug->getProductLeftBugsTest($buildIDList[3], $productIDList[1])) && p() && e('BUG121,BUG122,BUG123'); // 测试获取buildID为14 productID为41的bug
r($bug->getProductLeftBugsTest($buildIDList[4], $productIDList[0])) && p() && e('BUG1,BUG2,BUG3');       // 测试获取buildID为15 productID为1的bug
r($bug->getProductLeftBugsTest($buildIDList[5], $productIDList[0])) && p() && e('BUG1,BUG2,BUG3');       // 测试获取buildID为16 productID为1的bug
r($bug->getProductLeftBugsTest($buildIDList[6], $productIDList[0])) && p() && e('0');                    // 测试获取不存在的build的bug
r($bug->getProductLeftBugsTest($buildIDList[0], $productIDList[2])) && p() && e('0');                    // 测试获取不存在的product的bug
