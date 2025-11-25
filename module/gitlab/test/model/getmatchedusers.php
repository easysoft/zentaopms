#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';
su('admin');

/**

title=测试gitlabModel->getMatchedUsers();
timeout=0
cid=16653

- 获取gitlab服务器1匹配的用户列表。
 - 第1条的account属性 @root
 - 第1条的zentaoAccount属性 @user3
- 获取gitlab服务器1匹配的用户数量。 @3
- 当gitlab用户为空时获取gitlab服务器1匹配的用户数量。 @0
- 当禅道用户为空时获取gitlab服务器1匹配的用户数量。
 - 第4条的account属性 @user6
 - 第4条的zentaoAccount属性 @user6

*/

zenData('pipeline')->gen(5);
zenData('oauth')->loadYaml('oauth')->gen(5);
zenData('user')->gen(10);

global $app;
$app->rawModule = 'gitlab';
$app->rawMethod = 'browse';

$gitlab = new gitlabTest();

$gitlabID     = 1;
$gitlabUsers  = $gitlab->gitlab->apiGetUsers($gitlabID);
$zentaoUsers  = $gitlab->gitlab->dao->select('account,email,realname')->from(TABLE_USER)->where('deleted')->eq('0')->fetchAll('account');
$matchedUsers = $gitlab->getMatchedUsersTest($gitlabID, $gitlabUsers, $zentaoUsers);
r($matchedUsers)                                                  && p('1:account,zentaoAccount') && e('root,user3');  // 获取gitlab服务器1匹配的用户列表。
r(count($matchedUsers))                                           && p('') && e('3');                                  // 获取gitlab服务器1匹配的用户数量。
r($gitlab->getMatchedUsersTest($gitlabID, array(), $zentaoUsers)) && p('') && e('0');                                  // 当gitlab用户为空时获取gitlab服务器1匹配的用户数量。
r($gitlab->getMatchedUsersTest($gitlabID, $gitlabUsers, array())) && p('4:account,zentaoAccount') && e('user6,user6'); // 当禅道用户为空时获取gitlab服务器1匹配的用户数量。
