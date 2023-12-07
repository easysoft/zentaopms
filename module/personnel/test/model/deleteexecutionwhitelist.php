#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('acl')->config('acl')->gen(100);
zdTable('project')->config('project')->gen(150);
zdTable('userview')->gen(50);
zdTable('user')->gen(20);

/**

title=测试 personnelModel->deleteExecutionWhitelist();
cid=1
pid=1

*/

$personnel = new personnelTest('admin');

$executionIdList = array(0, 101, 102, 1111);
$account         = array('admin', 'user1', 'test26', 'user2');

r($personnel->deleteExecutionWhitelistTest($executionIdList[0], $account[0])) && p() && e('0'); // 从执行 0 删除账号 admin 的白名单
r($personnel->deleteExecutionWhitelistTest($executionIdList[0], $account[1])) && p() && e('0'); // 从执行 0 删除账号 user1 的白名单
r($personnel->deleteExecutionWhitelistTest($executionIdList[0], $account[2])) && p() && e('0'); // 从执行 0 删除账号 test26 的白名单
r($personnel->deleteExecutionWhitelistTest($executionIdList[0], $account[3])) && p() && e('0'); // 从执行 0 删除账号 user2 的白名单

r($personnel->deleteExecutionWhitelistTest($executionIdList[1], $account[0])) && p() && e(',user1,test26'); // 从执行 101 删除账号 admin 的白名单 admin不在白名单
r($personnel->deleteExecutionWhitelistTest($executionIdList[1], $account[1])) && p() && e(',test26');       // 从执行 101 删除账号 user1 的白名单 user1在白名单 且在acl有数据
r($personnel->deleteExecutionWhitelistTest($executionIdList[1], $account[2])) && p() && e(',test26');       // 从执行 101 删除账号 test26 的白名单 test26 在白名单 但在acl没有数据
r($personnel->deleteExecutionWhitelistTest($executionIdList[1], $account[3])) && p() && e(',test26');       // 从执行 101 删除账号 user2 的白名单 user2不在白名单 且在acl有数据

r($personnel->deleteExecutionWhitelistTest($executionIdList[2], $account[0])) && p() && e(',user2,test27'); // 从执行 102 删除账号 admin 的白名单
r($personnel->deleteExecutionWhitelistTest($executionIdList[2], $account[1])) && p() && e(',user2,test27'); // 从执行 102 删除账号 user1 的白名单
r($personnel->deleteExecutionWhitelistTest($executionIdList[2], $account[2])) && p() && e(',user2,test27'); // 从执行 102 删除账号 test26 的白名单
r($personnel->deleteExecutionWhitelistTest($executionIdList[2], $account[3])) && p() && e(',test27');       // 从执行 102 删除账号 user2 的白名单

r($personnel->deleteExecutionWhitelistTest($executionIdList[3], $account[0])) && p() && e('0'); // 从执行 1111 删除账号 admin 的白名单
r($personnel->deleteExecutionWhitelistTest($executionIdList[3], $account[1])) && p() && e('0'); // 从执行 1111 删除账号 user1 的白名单
r($personnel->deleteExecutionWhitelistTest($executionIdList[3], $account[2])) && p() && e('0'); // 从执行 1111 删除账号 test26 的白名单
r($personnel->deleteExecutionWhitelistTest($executionIdList[3], $account[3])) && p() && e('0'); // 从执行 1111 删除账号 user2 的白名单
