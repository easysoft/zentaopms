#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->getMatchedUsers();
timeout=0
cid=1

- 获取gitlab服务器1匹配的用户列表。
 - 第1条的account属性 @root
 - 第1条的zentaoAccount属性 @user3
- 获取gitlab服务器1匹配的用户数量。 @2
- 当gitlab用户为空时获取gitlab服务器1匹配的用户数量。 @0
- 当禅道用户为空时获取gitlab服务器1匹配的用户数量。
 - 第4条的account属性 @user6
 - 第4条的zentaoAccount属性 @user6

*/

zdTable('pipeline')->gen(5);
zdTable('oauth')->gen(5);
zdTable('user')->gen(10);

$gitlab = new gitlabTest();

$gitlabID     = 1;
$gitlabUsers  = $gitlab->gitlab->apiGetUsers($gitlabID);
$zentaoUsers  = $gitlab->gitlab->dao->select('account,email,realname')->from(TABLE_USER)->where('deleted')->eq('0')->fetchAll('account');
$matchedUsers = $gitlab->getMatchedUsersTest($gitlabID, $gitlabUsers, $zentaoUsers);
r($matchedUsers)                                                  && p('1:account,zentaoAccount') && e('root,user3');  // 获取gitlab服务器1匹配的用户列表。
r(count($matchedUsers))                                           && p('') && e('2');                                  // 获取gitlab服务器1匹配的用户数量。
r($gitlab->getMatchedUsersTest($gitlabID, array(), $zentaoUsers)) && p('') && e('0');                                  // 当gitlab用户为空时获取gitlab服务器1匹配的用户数量。
r($gitlab->getMatchedUsersTest($gitlabID, $gitlabUsers, array())) && p('4:account,zentaoAccount') && e('user6,user6'); // 当禅道用户为空时获取gitlab服务器1匹配的用户数量。