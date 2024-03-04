#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->apiGetSingleUser();
timeout=0
cid=1

- 查询正确的user信息属性name @测试用户1
- 使用不存在的gitlabID查询user信息 @0
- 使用不存在的user名称查询user信息属性message @404 User Not Found

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$user1 = $gitlab->apiGetSingleUserTest(1, 4);
$user2 = $gitlab->apiGetSingleUserTest(0, 2);
$user3 = $gitlab->apiGetSingleUserTest(1, 100001);

r($user1) && p('name')    && e('测试用户1');                   // 查询正确的user信息
r($user2) && p()          && e('0');                      // 使用不存在的gitlabID查询user信息
r($user3) && p('message') && e('404 User Not Found');      // 使用不存在的user名称查询user信息