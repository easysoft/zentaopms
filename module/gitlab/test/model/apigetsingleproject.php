#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->apiGetSingleProject();
timeout=0
cid=1

- 查询正确的project信息属性name @testHtml
- 使用不存在的gitlabID查询project信息 @0
- 使用不存在的projectID查询project信息属性message @404 Project Not Found
- 用普通用户查询有权限正确的project信息属性id @2
- 用普通用户查询无权限正确的project信息属性message @404 Project Not Found
- 第三个参数为fales普通用户查询无权限正确的project信息属性name @privateTest

*/

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);

$gitlab = new gitlabTest();

$project1 = $gitlab->apiGetSingleProjectTest(1, 2);
$project2 = $gitlab->apiGetSingleProjectTest(0, 2);
$project3 = $gitlab->apiGetSingleProjectTest(1, 0);
$project4 = $gitlab->apiGetSingleProjectTest(1, 2);

r($project1) && p('name')    && e('testHtml');              // 查询正确的project信息
r($project2) && p()          && e('0');                     // 使用不存在的gitlabID查询project信息
r($project3) && p('message') && e('404 Project Not Found'); // 使用不存在的projectID查询project信息

su('user6');
$project5 = $gitlab->apiGetSingleProjectTest(1, 2, true);
$project6 = $gitlab->apiGetSingleProjectTest(1, 19, true);
$project7 = $gitlab->apiGetSingleProjectTest(1, 5, false);
r($project5) && p('id')      && e('2');                     // 用普通用户查询有权限正确的project信息
r($project6) && p('message') && e('404 Project Not Found'); // 用普通用户查询无权限正确的project信息
r($project7) && p('name')    && e('privateTest');           // 第三个参数为fales普通用户查询无权限正确的project信息
