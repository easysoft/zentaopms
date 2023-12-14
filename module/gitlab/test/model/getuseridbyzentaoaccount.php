#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->getUserIDByZentaoAccount();
timeout=0
cid=1

- 根据禅道账号user3获取gitlab用户id。 @1
- 根据禅道账号user5获取gitlab用户id。 @3
- 根据禅道账号user3获取不存在的gitlab用户id。 @0

*/

zdTable('user')->gen(10);
zdTable('oauth')->gen(5);

$gitlab = new gitlabTest();

r($gitlab->getUserIDByZentaoAccountTest($gitlabID = 1, 'user3'))  && p() && e('1'); // 根据禅道账号user3获取gitlab用户id。
r($gitlab->getUserIDByZentaoAccountTest($gitlabID = 1, 'user5'))  && p() && e('3'); // 根据禅道账号user5获取gitlab用户id。
r($gitlab->getUserIDByZentaoAccountTest($gitlabID = 10, 'user3')) && p() && e('0'); // 根据禅道账号user3获取不存在的gitlab用户id。