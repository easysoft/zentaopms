#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel::checkName();
timeout=0
cid=1

- 使用不符合规则的名字 @0
- 使用符合规则的名字 @1

*/

zdTable('pipeline')->gen(5);

$repoModel = $tester->loadModel('repo');

$repo = new stdclass();
$repo->name = 'abc&&';
r($repoModel->checkName($repo)) && p() && e('0'); //使用不符合规则的名字

$repo->name = 'unitTestProject17';
r($repoModel->checkName($repo)) && p() && e('1'); //使用符合规则的名字