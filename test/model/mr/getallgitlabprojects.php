#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 mrModel::getAllGitlabProjects();
cid=1
pid=1

普通用户获取所有gitlab服务器项目列表 >> return empty

*/

$mrModel = $tester->loadModel('mr');

$result = $mrModel->getAllGitlabProjects();
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //普通用户获取所有gitlab服务器项目列表

su('admin');
$result = $mrModel->getAllGitlabProjects();
r(isset(array_shift($result[1])->name)) && p() && e(1); //管理员获取所有gitlab服务器项目列表