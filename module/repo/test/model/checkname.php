#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';
su('admin');

/**

title=测试 repoModel::checkName();
timeout=0
cid=1

- 使用不符合规则的名字 @0
- 使用符合规则的名字 @1

*/

zenData('pipeline')->gen(5);

$repoModel = $tester->loadModel('repo');

$name = 'abc&&';
r($repoModel->checkName($name)) && p() && e('0'); //使用不符合规则的名字

$name = 'unitTestProject17';
r($repoModel->checkName($name)) && p() && e('1'); //使用符合规则的名字
