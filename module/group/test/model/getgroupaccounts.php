#!/usr/bin/env php
<?php

/**

title=测试 groupModel->getGroupAccounts();
timeout=0
cid=1

- Group1,2 包含 account user1属性user1 @user1
- Group1,2 包含 account user3属性user2 @user2
- Group1,2 包含 account user6属性user6 @user6
- Group1,2 不包含 account user3属性user3 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

zdTable('user')->gen(100);
zdTable('group')->gen(5);
zdTable('usergroup')->config('usergroup')->gen(10);

$group = new groupTest();
$groupIdList = array(1,2);

r($group->getGroupAccountsTest($groupIdList)) && p('user1')  && e('user1');  // Group1,2 包含 account user1
r($group->getGroupAccountsTest($groupIdList)) && p('user2')  && e('user2');  // Group1,2 包含 account user3
r($group->getGroupAccountsTest($groupIdList)) && p('user6')  && e('user6');  // Group1,2 包含 account user6
r($group->getGroupAccountsTest($groupIdList)) && p('user3')  && e('~~');     // Group1,2 不包含 account user3
