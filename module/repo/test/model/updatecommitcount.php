#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->updateCommitCount();
timeout=0
cid=1

- 设置版本库1 commit计数
 - 属性id @1
 - 属性commits @5
- 设置版本库2 commit计数
 - 属性id @2
 - 属性commits @20

*/

zdTable('repo')->config('repo')->gen(5);

$repo = new repoTest();

$repoIds = array(1, 2);

r($repo->updateCommitCountTest($repoIds[0], 5))  && p('id,commits') && e('1,5');  //设置版本库1 commit计数
r($repo->updateCommitCountTest($repoIds[1], 20)) && p('id,commits') && e('2,20'); //设置版本库2 commit计数