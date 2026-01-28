#!/usr/bin/env php
<?php

/**

title=测试 docModel->getUserByConfluenceUserID();
timeout=0
cid=16135

- 获取admin的用户名
 - 属性account @admin
 - 属性realname @admin
- 获取用户1的用户名
 - 属性account @user1
 - 属性realname @用户1
- 获取用户2的用户名
 - 属性account @user2
 - 属性realname @用户2
- 获取用户3的用户名
 - 属性account @user3
 - 属性realname @用户3
- 获取用户4的用户名
 - 属性account @user4
 - 属性realname @用户4
- 获取不存在的用户 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

global $tester;
$docTester = $tester->loadModel('doc');
$tester->loadModel('convert')->createTmpTable4Jira();

$userPairs = array('admin' => 'admin', 'user1' => '用户1', 'user2' => '用户2', 'user3' => '用户3', 'user4' => '用户4', 'user5' => '用户5');

$userRelation = new stdClass();
$userRelation->AType = 'juser';
$userRelation->BType = 'zuser';
foreach($userPairs as $account => $userName)
{
    $userRelation->AID = $userName;
    $userRelation->BID = $account;
    $docTester->dao->insert('jiratmprelation')->data($userRelation)->exec();
}

r($docTester->getUserByConfluenceUserID('admin')) && p('account,realname') && e('admin,admin'); // 获取admin的用户名
r($docTester->getUserByConfluenceUserID('用户1')) && p('account,realname') && e('user1,用户1'); // 获取用户1的用户名
r($docTester->getUserByConfluenceUserID('用户2')) && p('account,realname') && e('user2,用户2'); // 获取用户2的用户名
r($docTester->getUserByConfluenceUserID('用户3')) && p('account,realname') && e('user3,用户3'); // 获取用户3的用户名
r($docTester->getUserByConfluenceUserID('用户4')) && p('account,realname') && e('user4,用户4'); // 获取用户4的用户名
r($docTester->getUserByConfluenceUserID('用户5')) && p() && e('0'); // 获取不存在的用户
$docTester->dbh->exec('DROP TABLE `jiratmprelation`;');
