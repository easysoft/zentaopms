#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';
su('admin');

/**

title=测试gitlabModel->updateCodePath();
timeout=0
cid=16668

- 更新版本库的代码地址属性path @https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml
- 使用不存在的gitlabID更新代码库的代码地址 @0
- 使用不存在的projectID更新代码库的代码地址 @0
- 更新版本库的代码地址 @http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml
- 更新版本库的代码地址 @http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml

*/

$repo = zenData('repo')->loadYaml('repo');
$repo->path->range('');
$repo->gen(5);

$gitlab = new gitlabTest();

$gitlabID  = 1;
$projectID = 2;
$repoID    = 1;

$result1 = $gitlab->updateCodePathTest($gitlabID, $projectID, $repoID);
$result2 = $gitlab->updateCodePathTest(0, $projectID, $repoID);
$result3 = $gitlab->updateCodePathTest($gitlabID, 0, $repoID);
$result4 = $gitlab->updateCodePathTest($gitlabID, $projectID, $repoID);
$result5 = $gitlab->updateCodePathTest($gitlabID, $projectID, $repoID);

r($result1) && p('path') && e('https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml'); // 更新版本库的代码地址
r($result2) && p()       && e('0'); // 使用不存在的gitlabID更新代码库的代码地址
r($result3) && p()       && e('0'); // 使用不存在的projectID更新代码库的代码地址
r($result4) && p('path') && e('https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml'); // 更新版本库的代码地址
r($result5) && p('path') && e('https://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml'); // 更新版本库的代码地址
