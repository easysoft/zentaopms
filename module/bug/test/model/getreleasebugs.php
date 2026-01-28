#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('bug')->gen(10);
zenData('product')->gen(10);
zenData('build')->gen(20);
zenData('project')->loadYaml('execution2')->gen(20);

/**

title=bugModel->getReleaseBugs();
timeout=0
cid=15394

- 测试获取buildID为11 productID为2的bug @BUG4,BUG5,BUG6

- 测试获取buildID为12 productID为2的bug @0
- 测试获取buildID为13 productID为1的bug @BUG1,BUG2,BUG3

- 测试获取buildID为14 productID为2的bug @BUG4,BUG5,BUG6

- 测试获取buildID为15 productID为1的bug @BUG1,BUG2,BUG3

- 测试获取buildID为16 productID为2的bug @BUG4,BUG5,BUG6

*/

$buildIDList   = array(11, 12, 13, 14, 15, 16);
$productIDList = array(1, 2);

$bug=new bugModelTest();
r($bug->getReleaseBugsTest($buildIDList[0], $productIDList[1])) && p('') && e('BUG4,BUG5,BUG6'); // 测试获取buildID为11 productID为2的bug
r($bug->getReleaseBugsTest($buildIDList[1], $productIDList[1])) && p('') && e('0');              // 测试获取buildID为12 productID为2的bug
r($bug->getReleaseBugsTest($buildIDList[2], $productIDList[0])) && p('') && e('BUG1,BUG2,BUG3'); // 测试获取buildID为13 productID为1的bug
r($bug->getReleaseBugsTest($buildIDList[3], $productIDList[1])) && p('') && e('BUG4,BUG5,BUG6'); // 测试获取buildID为14 productID为2的bug
r($bug->getReleaseBugsTest($buildIDList[4], $productIDList[0])) && p('') && e('BUG1,BUG2,BUG3'); // 测试获取buildID为15 productID为1的bug
r($bug->getReleaseBugsTest($buildIDList[5], $productIDList[1])) && p('') && e('BUG4,BUG5,BUG6'); // 测试获取buildID为16 productID为2的bug
