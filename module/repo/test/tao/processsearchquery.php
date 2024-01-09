#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 repoModel::processSearchQuery();
timeout=0
cid=1

- 使用空的queryID @1 = 1
- 使用正确的queryID @(( 1   AND `name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'
- 第二次使用空的queryID @(( 1   AND `name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'

*/

zdTable('userquery')->gen(5);

$queryID = 1;

$repoModel = $tester->loadModel('repo');
r($repoModel->processSearchQuery(0))        && p() && e('1 = 1'); //使用空的queryID
r($repoModel->processSearchQuery($queryID)) && p() && e("(( 1   AND `name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'"); //使用正确的queryID
r($repoModel->processSearchQuery(0))        && p() && e("(( 1   AND `name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'"); //第二次使用空的queryID
