#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';
su('admin');

/**

title=测试 repoModel->getByIdList();
timeout=0
cid=1

- 获取版本库1类型第1条的SCM属性 @Gitlab
- 获取版本库1类型第4条的password属性 @KXdOi8zgTcUqEFX2Hx8B
- 获取不存在版本库属性5 @~~
- 获取列表数量 @4

*/

zenData('repo')->loadYaml('repo')->gen(4);

$repo = $tester->loadModel('repo');

$idList = array(1, 2, 3, 4, 10001);
$result = $repo->getByIdList($idList);

r($result)        && p('1:SCM')      && e('Gitlab'); //获取版本库1类型
r($result)        && p('4:password') && e('KXdOi8zgTcUqEFX2Hx8B'); //获取版本库1类型
r($result)        && p('5')          && e('~~'); //获取不存在版本库
r(count($result)) && p()             && e('4'); //获取列表数量