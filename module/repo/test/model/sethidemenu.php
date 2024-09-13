#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';
su('admin');

/**

title=测试 repoModel->addLink();
timeout=0
cid=1

- Gitlab代码库显示标签菜单第tag条的link属性 @标签|repo|browsetag|repoID=0&objectID=%s
- Gitea代码库不显示标签菜单属性tag @~~
- SVN 代码库不显示标签菜单属性tag @0
- 没有权限，不显示标签 @0

*/

zenData('repo')->loadYaml('repo')->gen(5);
zenData('project')->loadYaml('execution')->gen(5);

$repoModel = new repoTest();

$tab = 'execution';
$tester->session->set('repoID', 1);
r($repoModel->setHideMenuTest($tab, 11)) && p('tag:link') && e('标签|repo|browsetag|repoID=0&objectID=%s'); // Gitlab代码库显示标签菜单
r($repoModel->setHideMenuTest($tab, 13)) && p('tag') && e('~~'); // Gitea代码库不显示标签菜单

$tab = 'project';
r($repoModel->setHideMenuTest($tab, 14)) && p('tag') && e('0'); // SVN 代码库不显示标签菜单

su('user1');
r($repoModel->setHideMenuTest($tab, 11)) && p() && e('0'); // 没有权限，不显示标签