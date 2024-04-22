#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::getGitlabProjects();
timeout=0
cid=1

- 使用正确的gitlabID,空的filter查询群组属性name @unittest1
- 普通用户使用正确的gitlabID,IS_DEVELOPER filter查询群组属性name @privateProject
- 普通用户使用正确的gitlabID,filter查询群组属性name @unittest1

*/

zenData('pipeline')->gen(5);
zenData('user')->gen(10);
zenData('repo')->loadYaml('repo')->gen(5);
zenData('oauth')->loadYaml('oauth')->gen(5);
su('admin');
$repo = $tester->loadModel('repo');

$gitlabID       = 1;
$projectFilters = array('IS_DEVELOPER', 'ALL');

$result = $repo->getGitlabProjects($gitlabID, '');
r(end($result)) && p('name') && e('unittest1'); //使用正确的gitlabID,空的filter查询群组

su('user6');
$result = $repo->getGitlabProjects($gitlabID, $projectFilters[0]);
r(end($result)) && p('name') && e('privateProject'); //普通用户使用正确的gitlabID,IS_DEVELOPER filter查询群组

$result = $repo->getGitlabProjects($gitlabID, $projectFilters[1]);
r(end($result)) && p('name') && e('unittest1'); //普通用户使用正确的gitlabID,filter查询群组