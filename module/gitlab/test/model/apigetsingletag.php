#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->apiGetSingleTag();
timeout=0
cid=1

- 查询正确的tag信息属性name @tag3
- 使用不存在的gitlabID查询tag信息 @0
- 使用不存在的projectID查询tag信息属性message @404 Project Not Found
- 使用不存在的tag名称查询tag信息属性message @404 Tag Not Found

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$tag1 = $gitlab->apiGetSingleTagTest(1, 2, 'tag3');
$tag2 = $gitlab->apiGetSingleTagTest(0, 2, 'tag3');
$tag3 = $gitlab->apiGetSingleTagTest(1, 0, 'tag3');
$tag4 = $gitlab->apiGetSingleTagTest(1, 2, '0');

r($tag1) && p('name')    && e('tag3');                   // 查询正确的tag信息
r($tag2) && p()          && e('0');                      // 使用不存在的gitlabID查询tag信息
r($tag3) && p('message') && e('404 Project Not Found');  // 使用不存在的projectID查询tag信息
r($tag4) && p('message') && e('404 Tag Not Found');      // 使用不存在的tag名称查询tag信息