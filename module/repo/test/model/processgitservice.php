#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->processGitService();
timeout=0
cid=1

- 测试获取gitlab版本库
 - 属性client @https://gitlabdev.qc.oop.cc
 - 属性codePath @http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml
 - 属性gitService @1

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$repo = new repoTest();

$gitlabID = 1;
$giteaID  = 3;

r($repo->processGitServiceTest($gitlabID)) && p('client,codePath,gitService') && e('https://gitlabdev.qc.oop.cc,http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml,1'); //测试获取gitlab版本库
 r($repo->processGitServiceTest($giteaID)) && p('codePath,project')                   && e('https://giteadev.qc.oop.cc/gitea/unittest,gitea/unittest'); //测试获取gitea版本库