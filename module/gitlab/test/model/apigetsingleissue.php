#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->apiGetSingleIssue();
timeout=0
cid=1

- 查询正确的issue信息属性title @issue1
- 使用不存在的gitlabID查询issue信息 @0
- 使用不存在的projectID查询issue信息属性message @404 Project Not Found
- 使用不存在的issue名称查询issue信息属性message @404 Not found

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$issue1 = $gitlab->apiGetSingleIssueTest(1, 2, 1);
$issue2 = $gitlab->apiGetSingleIssueTest(0, 2, 1);
$issue3 = $gitlab->apiGetSingleIssueTest(1, 0, 1);
$issue4 = $gitlab->apiGetSingleIssueTest(1, 2, 10001);

r($issue1) && p('title')   && e('issue1');                // 查询正确的issue信息
r($issue2) && p()          && e('0');                     // 使用不存在的gitlabID查询issue信息
r($issue3) && p('message') && e('404 Project Not Found'); // 使用不存在的projectID查询issue信息
r($issue4) && p('message') && e('404 Not found');   // 使用不存在的issue名称查询issue信息