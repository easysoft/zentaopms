#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 mrModel::getList();
cid=1
pid=1

普通用户获取mr列表 >> return empty
管理员获取mr列表 >> Test MR

*/

$tester->app->loadClass('pager', $static = true);
$mrModel = $tester->loadModel('mr');

$mode    = 'all';
$param   = 'all';
$orderBy = 'id_desc';
$pager   = new pager(0, 20, 1);

$projects = $mrModel->getAllGitlabProjects();
$result   = $mrModel->getList($mode, $param, $orderBy, $pager, empty($projects) ? false : $projects);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //普通用户获取mr列表

su('admin');
$projects = $mrModel->getAllGitlabProjects();
$result   = $mrModel->getList($mode, $param, $orderBy, $pager, empty($projects) ? false : $projects);
r(array_shift($result)) && p('title') && e('Test MR'); //管理员获取mr列表