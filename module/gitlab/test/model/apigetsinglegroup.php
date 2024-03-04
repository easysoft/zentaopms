#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->apiGetSingleGroup();
timeout=0
cid=1

- 查询正确的group信息属性name @testGroup
- 使用不存在的gitlabID查询group信息 @0
- 使用不存在的group名称查询group信息属性message @404 Group Not Found

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$group1 = $gitlab->apiGetSingleGroupTest(1, 14);
$group2 = $gitlab->apiGetSingleGroupTest(0, 2);
$group3 = $gitlab->apiGetSingleGroupTest(1, 100001);

r($group1) && p('name')    && e('testGroup');           // 查询正确的group信息
r($group2) && p()          && e('0');                   // 使用不存在的gitlabID查询group信息
r($group3) && p('message') && e('404 Group Not Found'); // 使用不存在的group名称查询group信息