#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->getUserAccountIdPairs();
timeout=0
cid=1

- 获取用户名为user3绑定的gitlab用户id。属性user3 @1
- 获取用户名为user5绑定的gitlab用户id。属性user5 @3
- 获取GitLab服务器不存在的绑定。属性user3 @0

*/

zdTable('user')->gen(10);
zdTable('oauth')->gen(5);

$gitlab = new gitlabTest();

r($gitlab->getUserAccountIdPairsTest($gitlabID = 1))  && p('user3') && e('1'); // 获取用户名为user3绑定的gitlab用户id。
r($gitlab->getUserAccountIdPairsTest($gitlabID = 1))  && p('user5') && e('3'); // 获取用户名为user5绑定的gitlab用户id。
r($gitlab->getUserAccountIdPairsTest($gitlabID = 10)) && p('user3') && e('0'); // 获取GitLab服务器不存在的绑定。