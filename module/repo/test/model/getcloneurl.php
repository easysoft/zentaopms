#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getCloneUrl();
timeout=0
cid=1

- 获取gitlab项目2 clone url属性http @http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml.git
- 获取gitlab项目1 clone url属性http @http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/Monitoring.git
- 获取gitea项目 clone url属性http @https://giteadev.qc.oop.cc/gitea/unittest.git
- 获取svn项目clone url属性svn @https://svn.qc.oop.cc/svn/unittest/
- 获取空项目 @empty

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$repo = new repoTest();
$result1 = $repo->getCloneUrlTest(1);
$result2 = $repo->getCloneUrlTest(2);
$result3 = $repo->getCloneUrlTest(3);
$result4 = $repo->getCloneUrlTest(4);
$result5 = $repo->getCloneUrlTest(0);

r($result1) && p('http') && e('http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml.git');   //获取gitlab项目2 clone url
r($result2) && p('http') && e('http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/Monitoring.git'); //获取gitlab项目1 clone url
r($result3) && p('http') && e('https://giteadev.qc.oop.cc/gitea/unittest.git'); //获取gitea项目 clone url
r($result4) && p('svn')  && e('https://svn.qc.oop.cc/svn/unittest/'); //获取svn项目clone url
r($result5) && p()       && e('empty'); //获取空项目