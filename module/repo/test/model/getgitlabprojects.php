#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 gitlabModel::getGitlabProjects();
timeout=0
cid=1

- 使用正确的gitlabID,空的filter查询群组属性name @Monitoring
- 普通用户使用正确的gitlabID,IS_DEVELOPER filter查询群组属性name @testHtml
- 普通用户使用正确的gitlabID,filter查询群组属性name @Monitoring

*/

zdTable('pipeline')->gen(5);
zdTable('user')->gen(10);
zdTable('oauth')->config('oauth')->gen(5);
$repo = $tester->loadModel('repo');

$gitlabID       = 1;
$projectFilters = array('IS_DEVELOPER', 'ALL');

$result = $repo->getGitlabProjects($gitlabID, '');
r(end($result)) && p('name') && e('Monitoring'); //使用正确的gitlabID,空的filter查询群组
su('user6');
$result = $repo->getGitlabProjects($gitlabID, $projectFilters[0]);
r(end($result)) && p('name') && e('testHtml'); //普通用户使用正确的gitlabID,IS_DEVELOPER filter查询群组
$result = $repo->getGitlabProjects($gitlabID, $projectFilters[1]);
r(end($result)) && p('name') && e('Monitoring'); //普通用户使用正确的gitlabID,filter查询群组
