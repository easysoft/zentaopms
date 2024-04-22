#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getImportedProjects();
timeout=0
cid=1

- 查询已关联的版本库服务器ID为1的版本库列表属性1 @1
- 查询未关联的版本库服务器ID为2的版本库列表，结果应该为空属性1 @0

*/

zdTable('repo')->config('repo')->gen(4);

$repo = $tester->loadModel('repo');

r($repo->getImportedProjects(1)) && p('1') && e('1'); // 查询已关联的版本库服务器ID为1的版本库列表
r($repo->getImportedProjects(2)) && p('1') && e('0'); // 查询未关联的版本库服务器ID为2的版本库列表，结果应该为空