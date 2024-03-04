#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getByID();
timeout=0
cid=1

- 获取gitlab版本库codePath属性codePath @http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml
- 获取gitea版本库项目属性serviceProject @gitea/unittest
- 获取svn版本库密码属性password @KXdOi8zgTcUqEFX2Hx8B
- 获取不存在版本库 @0

*/

zdTable('repo')->config('repo')->gen(4);

$repo = $tester->loadModel('repo');

$idList = array(1, 2, 3, 4, 10001);
$result = $repo->getByIdList($idList);

r($repo->getByID(1))     && p('codePath')       && e('http://gitlabdev.qc.oop.cc/gitlab-instance-76af86df/testhtml'); //获取gitlab版本库codePath
r($repo->getByID(3))     && p('serviceProject') && e('gitea/unittest'); //获取gitea版本库项目
r($repo->getByID(4))     && p('password')       && e('KXdOi8zgTcUqEFX2Hx8B'); //获取svn版本库密码
r($repo->getByID(10001)) && p()                 && e('0'); //获取不存在版本库