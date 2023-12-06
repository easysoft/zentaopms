#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('acl')->gen(50);
zdTable('user')->gen(50);

su('admin');

/**

title=测试 personnelModel->getWhitelist();
cid=1
pid=1

*/

$personnel = new personnelTest();

$objectIdList   = array(1, 2, 3, 4);
$objectTypeList = array('program', 'project', 'product', 'sprint');
$orderByList    = array('id_asc', 'id_desc');

r($personnel->getWhitelistTest($objectIdList[0], $objectTypeList[0], $orderByList[0])) && p() && e('admin,user20,user40'); // 获取 项目集 1 白名单人员account
r($personnel->getWhitelistTest($objectIdList[0], $objectTypeList[0], $orderByList[1])) && p() && e('user40,user20,admin'); // 获取 项目集 1 白名单人员account
r($personnel->getWhitelistTest($objectIdList[0], $objectTypeList[1], $orderByList[0])) && p() && e('user10,user30');       // 获取 项目   1 白名单人员account
r($personnel->getWhitelistTest($objectIdList[0], $objectTypeList[1], $orderByList[1])) && p() && e('user30,user10');       // 获取 项目   1 白名单人员account
r($personnel->getWhitelistTest($objectIdList[0], $objectTypeList[2], $orderByList[0])) && p() && e('0');                   // 获取 产品   1 白名单人员account
r($personnel->getWhitelistTest($objectIdList[0], $objectTypeList[2], $orderByList[1])) && p() && e('0');                   // 获取 产品   1 白名单人员account
r($personnel->getWhitelistTest($objectIdList[0], $objectTypeList[3], $orderByList[0])) && p() && e('0');                   // 获取 执行   1 白名单人员account
r($personnel->getWhitelistTest($objectIdList[0], $objectTypeList[3], $orderByList[1])) && p() && e('0');                   // 获取 执行   1 白名单人员account

r($personnel->getWhitelistTest($objectIdList[1], $objectTypeList[0], $orderByList[0])) && p() && e('0');                   // 获取 项目集 2 白名单人员account
r($personnel->getWhitelistTest($objectIdList[1], $objectTypeList[0], $orderByList[1])) && p() && e('0');                   // 获取 项目集 2 白名单人员account
r($personnel->getWhitelistTest($objectIdList[1], $objectTypeList[1], $orderByList[0])) && p() && e('0');                   // 获取 项目   2 白名单人员account
r($personnel->getWhitelistTest($objectIdList[1], $objectTypeList[1], $orderByList[1])) && p() && e('0');                   // 获取 项目   2 白名单人员account
r($personnel->getWhitelistTest($objectIdList[1], $objectTypeList[2], $orderByList[0])) && p() && e('user1,user21,user41'); // 获取 产品   2 白名单人员account
r($personnel->getWhitelistTest($objectIdList[1], $objectTypeList[2], $orderByList[1])) && p() && e('user41,user21,user1'); // 获取 产品   2 白名单人员account
r($personnel->getWhitelistTest($objectIdList[1], $objectTypeList[3], $orderByList[0])) && p() && e('user11,user31');       // 获取 执行   2 白名单人员account
r($personnel->getWhitelistTest($objectIdList[1], $objectTypeList[3], $orderByList[1])) && p() && e('user31,user11');       // 获取 执行   2 白名单人员account

r($personnel->getWhitelistTest($objectIdList[2], $objectTypeList[0], $orderByList[0])) && p() && e('user12,user32');       // 获取 项目集 3 白名单人员account
r($personnel->getWhitelistTest($objectIdList[2], $objectTypeList[0], $orderByList[1])) && p() && e('user32,user12');       // 获取 项目集 3 白名单人员account
r($personnel->getWhitelistTest($objectIdList[2], $objectTypeList[1], $orderByList[0])) && p() && e('user2,user22,user42'); // 获取 项目   3 白名单人员account
r($personnel->getWhitelistTest($objectIdList[2], $objectTypeList[1], $orderByList[1])) && p() && e('user42,user22,user2'); // 获取 项目   3 白名单人员account
r($personnel->getWhitelistTest($objectIdList[2], $objectTypeList[2], $orderByList[0])) && p() && e('0');                   // 获取 产品   3 白名单人员account
r($personnel->getWhitelistTest($objectIdList[2], $objectTypeList[2], $orderByList[1])) && p() && e('0');                   // 获取 产品   3 白名单人员account
r($personnel->getWhitelistTest($objectIdList[2], $objectTypeList[3], $orderByList[0])) && p() && e('0');                   // 获取 执行   3 白名单人员account
r($personnel->getWhitelistTest($objectIdList[2], $objectTypeList[3], $orderByList[1])) && p() && e('0');                   // 获取 执行   3 白名单人员account

r($personnel->getWhitelistTest($objectIdList[3], $objectTypeList[0], $orderByList[0])) && p() && e('0');                   // 获取 项目集 4 白名单人员account
r($personnel->getWhitelistTest($objectIdList[3], $objectTypeList[0], $orderByList[1])) && p() && e('0');                   // 获取 项目集 4 白名单人员account
r($personnel->getWhitelistTest($objectIdList[3], $objectTypeList[1], $orderByList[0])) && p() && e('0');                   // 获取 项目   4 白名单人员account
r($personnel->getWhitelistTest($objectIdList[3], $objectTypeList[1], $orderByList[1])) && p() && e('0');                   // 获取 项目   4 白名单人员account
r($personnel->getWhitelistTest($objectIdList[3], $objectTypeList[2], $orderByList[0])) && p() && e('user13,user33');       // 获取 产品   4 白名单人员account
r($personnel->getWhitelistTest($objectIdList[3], $objectTypeList[2], $orderByList[1])) && p() && e('user33,user13');       // 获取 产品   4 白名单人员account
r($personnel->getWhitelistTest($objectIdList[3], $objectTypeList[3], $orderByList[0])) && p() && e('user3,user23,user43'); // 获取 执行   4 白名单人员account
r($personnel->getWhitelistTest($objectIdList[3], $objectTypeList[3], $orderByList[1])) && p() && e('user43,user23,user3'); // 获取 执行   4 白名单人员account
