#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('acl')->config('acl')->gen(100);
zdTable('product')->config('product')->gen(50);
zdTable('project')->config('project')->gen(120);
zdTable('userview')->gen(50);
zdTable('user')->gen(20);


/**

title=测试 personnelModel->deleteProgramWhitelist();
cid=1
pid=1

*/

$personnel = new personnelTest('admin');

$programID = array(0, 1, 2, 1111);
$account   = array('admin', 'user1', 'test26');

r($personnel->deleteProgramWhitelistTest($programID[0], $account[0])) && p() && e('0'); // 测试删除programID为 0，account为 admin 的白名单信息 acl表不存在admin
r($personnel->deleteProgramWhitelistTest($programID[0], $account[1])) && p() && e('0'); // 测试删除programID为 0，account为 user1 的白名单信息
r($personnel->deleteProgramWhitelistTest($programID[0], $account[2])) && p() && e('0'); // 测试删除programID为 0，account为 test26 的白名单信息

r($personnel->deleteProgramWhitelistTest($programID[1], $account[0])) && p() && e(',user1,test26'); // 测试删除programID为 1，account为 admin 的白名单信息
r($personnel->deleteProgramWhitelistTest($programID[1], $account[1])) && p() && e(',test26');       // 测试删除programID为 1，account为 user1 的白名单信息
r($personnel->deleteProgramWhitelistTest($programID[1], $account[2])) && p() && e('0');             // 测试删除programID为 1，account为 test26 的白名单信息

r($personnel->deleteProgramWhitelistTest($programID[2], $account[0])) && p() && e(',user2,test27'); // 测试删除 programID为 2，account为 admin 的白名单信息 admin不在白名单内
r($personnel->deleteProgramWhitelistTest($programID[2], $account[1])) && p() && e(',user2,test27'); // 测试删除 programID为 2，account为 user1 的白名单信息 user1不在白名单内
r($personnel->deleteProgramWhitelistTest($programID[2], $account[2])) && p() && e(',user2,test27'); // 测试删除 programID为 2，account为 test26 的白名单信息 test26不在白名单内

r($personnel->deleteProgramWhitelistTest($programID[3], $account[0])) && p() && e('0'); // 测试删除 不存在的 programID为 1111，account为 admin 的白名单信息
r($personnel->deleteProgramWhitelistTest($programID[3], $account[1])) && p() && e('0'); // 测试删除 不存在的 programID为 1111，account为 user1 的白名单信息
r($personnel->deleteProgramWhitelistTest($programID[3], $account[2])) && p() && e('0'); // 测试删除 不存在的 programID为 1111，account为 test26 的白名单信息
