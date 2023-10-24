#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('acl')->config('acl')->gen(100);

/**

title=测试 personnelModel->deleteProgramWhitelist();
cid=1
pid=1

我这里通过add方法创建了一个id为10的项目集白名单，并修改source为同步，然后删除创建的这条信息 >> 0
传入空时这里我删除了一个objectID为0的，program白名单信息，如果没这条数据跳过 >> 0

*/

$personnel = new personnelTest('admin');

$programID = array(0, 1, 3);
$account   = array('admin', 'user1', 'user2');

r($personnel->deleteProgramWhitelistTest($programID[0], $account[0])) && p() && e('-1'); //测试删除programID为0，account为admin的白名单信息
r($personnel->deleteProgramWhitelistTest($programID[1], $account[0])) && p() && e('-1'); //测试删除programID为1，account为admin的白名单信息
r($personnel->deleteProgramWhitelistTest($programID[1], $account[1])) && p() && e('0');  //测试删除programID为3，account为admin的白名单信息
r($personnel->deleteProgramWhitelistTest($programID[1], $account[2])) && p() && e('-1'); //测试删除programID为3，account为admin的白名单信息
