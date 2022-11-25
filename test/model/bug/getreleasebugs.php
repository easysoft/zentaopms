#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getReleaseBugs();
cid=1
pid=1

测试获取buildID为11 productID为2的bug >> BUG4,BUG5,BUG6
测试获取buildID为12 productID为2的bug >> 0
测试获取buildID为13 productID为1的bug >> BUG1,BUG2,BUG3
测试获取buildID为14 productID为2的bug >> BUG4,BUG5,BUG6
测试获取buildID为15 productID为1的bug >> BUG1,BUG2,BUG3
测试获取buildID为16 productID为2的bug >> BUG4,BUG5,BUG6

*/

$buildIDList   = array('11', '12', '13', '14', '15', '16');
$productIDList = array('1', '2');

$bug=new bugTest();
r($bug->getReleaseBugsTest($buildIDList[0], $productIDList[1])) && p('') && e('BUG4,BUG5,BUG6'); // 测试获取buildID为11 productID为2的bug
r($bug->getReleaseBugsTest($buildIDList[1], $productIDList[1])) && p('') && e('0');              // 测试获取buildID为12 productID为2的bug
r($bug->getReleaseBugsTest($buildIDList[2], $productIDList[0])) && p('') && e('BUG1,BUG2,BUG3'); // 测试获取buildID为13 productID为1的bug
r($bug->getReleaseBugsTest($buildIDList[3], $productIDList[1])) && p('') && e('BUG4,BUG5,BUG6'); // 测试获取buildID为14 productID为2的bug
r($bug->getReleaseBugsTest($buildIDList[4], $productIDList[0])) && p('') && e('BUG1,BUG2,BUG3'); // 测试获取buildID为15 productID为1的bug
r($bug->getReleaseBugsTest($buildIDList[5], $productIDList[1])) && p('') && e('BUG4,BUG5,BUG6'); // 测试获取buildID为16 productID为2的bug