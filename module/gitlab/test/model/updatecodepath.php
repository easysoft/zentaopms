#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->updateCodePath();
timeout=0
cid=1

- 更新版本库的代码地址属性path @http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml
- 使用不存在的gitlabID更新代码库的代码地址 @0
- 使用不存在的projectID更新代码库的代码地址 @0

*/

$repo = zdTable('repo')->config('repo');
$repo->path->range('');
$repo->gen(4);

$gitlab = new gitlabTest();

$gitlabID  = 1;
$projectID = 2;
$repoID    = 1;

$result1 = $gitlab->updateCodePathTest($gitlabID, $projectID, $repoID);
$result2 = $gitlab->updateCodePathTest(0, $projectID, $repoID);
$result3 = $gitlab->updateCodePathTest($gitlabID, 0, $repoID);

r($result1) && p('path') && e('http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml'); // 更新版本库的代码地址
r($result2) && p()       && e('0'); // 使用不存在的gitlabID更新代码库的代码地址
r($result3) && p()       && e('0'); // 使用不存在的projectID更新代码库的代码地址