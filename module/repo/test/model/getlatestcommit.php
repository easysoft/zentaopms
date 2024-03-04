#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getLatestCommit();
timeout=0
cid=1

- 获取giltab版本库最后一次提交
 - 属性id @1
 - 属性revision @c808480afe22d3a55d94e91c59a8f3170212ade0
- 获取gitea版本库最后一次提交
 - 属性id @2
 - 属性commit @2

*/

zdTable('repo')->config('repo')->gen(4);
zdTable('repohistory')->config('repohistory')->gen(3);

$repoIds = array(1, 3);

$repo = new repoTest();
r($repo->getLatestCommitTest($repoIds[0])) && p('id,revision') && e('1,c808480afe22d3a55d94e91c59a8f3170212ade0'); //获取giltab版本库最后一次提交
r($repo->getLatestCommitTest($repoIds[1])) && p('id,commit')   && e('2,2'); //获取gitea版本库最后一次提交