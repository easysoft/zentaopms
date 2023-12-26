#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getByIdList();
timeout=0
cid=1

- 获取版本库1类型第0条的SCM属性 @Gitlab
- 获取版本库1类型第3条的password属性 @KXdOi8zgTcUqEFX2Hx8B
- 获取不存在版本库属性4 @~~
- 获取列表数量 @4

*/

zdTable('repo')->config('repo')->gen(4);

$repo = $tester->loadModel('repo');

$idList = array(1, 2, 3, 4, 10001);
$result = $repo->getByIdList($idList);

r($result)        && p('0:SCM')      && e('Gitlab'); //获取版本库1类型
r($result)        && p('3:password') && e('KXdOi8zgTcUqEFX2Hx8B'); //获取版本库1类型
r($result)        && p('4')          && e('~~'); //获取不存在版本库
r(count($result)) && p()             && e('4'); //获取列表数量