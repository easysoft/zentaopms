#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getModuleOwner();
cid=1
pid=1

查询模块1821 产品1的owner >> test1
查询模块1821 产品2的owner >> test2
查询模块1821 产品3的owner >> test3
查询模块1821 不存在的产品1000001的owner >> 0
查询模块1821 产品1的owner >> test1
查询模块1821 产品2的owner >> test2
查询模块1821 产品3的owner >> test3
查询模块1821 不存在的产品1000001的owner >> 0
查询模块1821 产品1的owner >> test1
查询模块1821 产品2的owner >> test2
查询模块1821 产品3的owner >> test3
查询模块1821 不存在的产品1000001的owner >> 0

*/

$moduleIDList  = array('1821', '1825', '1829');
$productIDList = array('1', '2', '3', '1000001');

$bug=new bugTest();

r($bug->getModuleOwnerTest($moduleIDList[0], $productIDList[0])) && p() && e('test1'); // 查询模块1821 产品1的owner
r($bug->getModuleOwnerTest($moduleIDList[0], $productIDList[1])) && p() && e('test2'); // 查询模块1821 产品2的owner
r($bug->getModuleOwnerTest($moduleIDList[0], $productIDList[2])) && p() && e('test3'); // 查询模块1821 产品3的owner
r($bug->getModuleOwnerTest($moduleIDList[0], $productIDList[3])) && p() && e('0');     // 查询模块1821 不存在的产品1000001的owner
r($bug->getModuleOwnerTest($moduleIDList[1], $productIDList[0])) && p() && e('test1'); // 查询模块1821 产品1的owner
r($bug->getModuleOwnerTest($moduleIDList[1], $productIDList[1])) && p() && e('test2'); // 查询模块1821 产品2的owner
r($bug->getModuleOwnerTest($moduleIDList[1], $productIDList[2])) && p() && e('test3'); // 查询模块1821 产品3的owner
r($bug->getModuleOwnerTest($moduleIDList[1], $productIDList[3])) && p() && e('0');     // 查询模块1821 不存在的产品1000001的owner
r($bug->getModuleOwnerTest($moduleIDList[2], $productIDList[0])) && p() && e('test1'); // 查询模块1821 产品1的owner
r($bug->getModuleOwnerTest($moduleIDList[2], $productIDList[1])) && p() && e('test2'); // 查询模块1821 产品2的owner
r($bug->getModuleOwnerTest($moduleIDList[2], $productIDList[2])) && p() && e('test3'); // 查询模块1821 产品3的owner
r($bug->getModuleOwnerTest($moduleIDList[2], $productIDList[3])) && p() && e('0');     // 查询模块1821 不存在的产品1000001的owner