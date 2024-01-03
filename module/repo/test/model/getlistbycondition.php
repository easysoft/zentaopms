#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getListByCondition();
timeout=0
cid=1

- 根据query查询 @0
- query为空时查询第1条的name属性 @testHtml
- 排序条件查询属性id @1
- 按分页查询属性id @3

*/

zdTable('repo')->config('repo')->gen(5);

$repo = $tester->loadModel('repo');
$repo->app->moduleName = 'repo';
$repo->app->methodName = 'browse';

$repoQuery = "path like '%123%'";
$orderBy   = 'id_asc';

$pager = new stdclass();
$pager->recPerPage = 2;
$pager->pageID     = 2;
$repo->app->loadClass('pager', true);
$pager = pager::init(0, $pager->recPerPage, $pager->pageID);

r($repo->getListByCondition($repoQuery, $SCM = 'Gitlab')) && p() && e('0'); //根据query查询
r($repo->getListByCondition('', $SCM = 'Gitlab')) && p('1:name') && e('testHtml'); //query为空时查询

$result = $repo->getListByCondition('', $SCM = 'Gitlab', $orderBy);
r(array_shift($result)) && p('id') && e(1); //排序条件查询

$result = $repo->getListByCondition('', $SCM = 'Gitlab,Gitea', $orderBy, $pager);
r(array_shift($result)) && p('id') && e(3); //按分页查询
