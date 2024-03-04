#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::getGitlabGroups();
timeout=0
cid=1

- 使用正确的gitlabID查询群组第0条的text属性 @GitLab Instance
- 使用正确的gitlabID查询群组数量 @1
- 使用错误的gitlabID查询 @0

*/

zdTable('pipeline')->gen(5);

$repo = $tester->loadModel('repo');

$gitlabID = 1;

$result = $repo->getGitlabGroups($gitlabID);
r($result)                   && p('0:text') && e('GitLab Instance'); //使用正确的gitlabID查询群组
r(count($result) > 1)        && p()         && e('1'); //使用正确的gitlabID查询群组数量
r($repo->getGitlabGroups(0)) && p()         && e('0'); //使用错误的gitlabID查询