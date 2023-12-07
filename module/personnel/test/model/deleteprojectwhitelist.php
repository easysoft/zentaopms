#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('acl')->config('acl')->gen(100);
zdTable('project')->config('project')->gen(50);
zdTable('userview')->gen(50);
zdTable('user')->gen(20);


/**

title=测试 personnelModel->deleteProjectWhitelist();
cid=1
pid=1

*/

$personnel = new personnelTest('admin');

$projectIdList = array(0, 11, 16, 1000);
$account       = array('admin', 'user11', 'user12');

r($personnel->deleteProjectWhitelistTest($projectIdList[0], $account[0])) && p() && e('0'); // 从项目 0 删除账号 admin 的白名单
r($personnel->deleteProjectWhitelistTest($projectIdList[0], $account[1])) && p() && e('0'); // 从项目 0 删除账号 user11 的白名单
r($personnel->deleteProjectWhitelistTest($projectIdList[0], $account[2])) && p() && e('0'); // 从项目 0 删除账号 user12 的白名单

r($personnel->deleteProjectWhitelistTest($projectIdList[1], $account[0])) && p() && e(',user11,test36'); // 从项目 11 删除账号 admin 的白名单
r($personnel->deleteProjectWhitelistTest($projectIdList[1], $account[1])) && p() && e(',test36');        // 从项目 11 删除账号 user11 的白名单
r($personnel->deleteProjectWhitelistTest($projectIdList[1], $account[2])) && p() && e(',test36');        // 从项目 11 删除账号 user12 的白名单

r($personnel->deleteProjectWhitelistTest($projectIdList[2], $account[0])) && p() && e(',user12,test37'); // 从项目 16 删除账号 admin 的白名单
r($personnel->deleteProjectWhitelistTest($projectIdList[2], $account[1])) && p() && e(',user12,test37'); // 从项目 16 删除账号 user11 的白名单
r($personnel->deleteProjectWhitelistTest($projectIdList[2], $account[2])) && p() && e(',user16,test41'); // 从项目 16 删除账号 user12 的白名单

r($personnel->deleteProjectWhitelistTest($projectIdList[3], $account[0])) && p() && e('0'); // 从项目 1000 删除账号 admin 的白名单
r($personnel->deleteProjectWhitelistTest($projectIdList[3], $account[1])) && p() && e('0'); // 从项目 1000 删除账号 user11 的白名单
r($personnel->deleteProjectWhitelistTest($projectIdList[3], $account[2])) && p() && e('0'); // 从项目 1000 删除账号 user12 的白名单
