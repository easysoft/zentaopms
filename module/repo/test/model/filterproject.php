#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->filterProject();
timeout=0
cid=8

- 获取版本库1项目属性11 @项目11
- 获取版本库2项目属性12 @项目12
- 获取空版本库项目 @0

*/

zdTable('pipeline')->gen(5);
zdTable('product')->gen(20);
zdTable('project')->gen(20);
zdTable('repo')->config('repo')->gen(4);

$repo = new repoTest();

$repoIds = array(1, 2);

r($repo->filterProjectTest($repoIds[0])) && p('11') && e('项目11'); //获取版本库1项目
r($repo->filterProjectTest($repoIds[1])) && p('12') && e('项目12'); //获取版本库2项目
r($repo->filterProjectTest(0))           && p()     && e('0');      //获取空版本库项目