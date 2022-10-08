#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';
su('admin');

/**

title=测试 projectModel->getBrotherProjects();
cid=1
pid=1

获取项目id为11同一个项目集下的所有项目个数 >> 9
获取id为731的顶级项目兄弟项目个数 >> 1

*/

global $tester;
$tester->app->loadConfig('execution');
$project = new Project();

$projectInst     = $project->project->getById(11);
$programProjects = $project->getBrotherProjectsTest($projectInst);
$projectInst     = $project->project->getById(731);
$depProjects     = $project->getBrotherProjectsTest($projectInst);

r(count($programProjects)) && p() && e('9'); // 获取项目id为11同一个项目集下的所有项目个数
r(count($depProjects))     && p() && e('1'); // 获取id为731的顶级项目兄弟项目个数