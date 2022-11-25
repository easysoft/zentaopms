#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 repoModel::getCommits();
cid=1
pid=1

通过repo,path,获取commit列表 >> 1
通过repo,path,获取commit数量 >> 1

*/

$repoModel = $tester->loadModel('repo');

$repoID = 1;
$repo   = $repoModel->getRepoByID($repoID);
$path   = '';

$result      = $repoModel->getCommits($repo, $path);
$firstResult = array_shift($result);
r(isset($firstResult->commit)) && p() && e('1'); //通过repo,path,获取commit列表
r(count($result) > 0)          && p() && e('1'); //通过repo,path,获取commit数量