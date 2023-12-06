#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('acl')->gen(50);

/**

title=测试 personnelModel->getWhitelistAccount();
cid=1
pid=1

*/

$personnel = new personnelTest('admin');

$objectIdList   = array(1, 2, 3, 4);
$objectTypeList = array('program', 'project', 'product', 'sprint');

r($personnel->getWhitelistAccountTest($objectIdList[0], $objectTypeList[0])) && p()  && e('admin,user20,user40'); // 测试获取 项目集 1 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[0], $objectTypeList[1])) && p()  && e('user10,user30');       // 测试获取 项目   1 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[0], $objectTypeList[2])) && p()  && e('0');                   // 测试获取 产品   1 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[0], $objectTypeList[3])) && p()  && e('0');                   // 测试获取 执行   1 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[1], $objectTypeList[0])) && p()  && e('0');                   // 测试获取 项目集 2 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[1], $objectTypeList[1])) && p()  && e('0');                   // 测试获取 项目   2 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[1], $objectTypeList[2])) && p()  && e('user1,user21,user41'); // 测试获取 产品   2 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[1], $objectTypeList[3])) && p()  && e('user11,user31');       // 测试获取 执行   2 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[2], $objectTypeList[0])) && p()  && e('user12,user32');       // 测试获取 项目集 3 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[2], $objectTypeList[1])) && p()  && e('user2,user22,user42'); // 测试获取 项目   3 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[2], $objectTypeList[2])) && p()  && e('0');                   // 测试获取 产品   3 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[2], $objectTypeList[3])) && p()  && e('0');                   // 测试获取 执行   3 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[3], $objectTypeList[0])) && p()  && e('0');                   // 测试获取 项目集 4 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[3], $objectTypeList[1])) && p()  && e('0');                   // 测试获取 项目   4 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[3], $objectTypeList[2])) && p()  && e('user13,user33');       // 测试获取 产品   4 的白名单人员
r($personnel->getWhitelistAccountTest($objectIdList[3], $objectTypeList[3])) && p()  && e('user3,user23,user43'); // 测试获取 执行   4 的白名单人员
