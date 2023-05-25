#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

/**

title=测试 projectModel::update();
cid=1
pid=1

正常更新项目的情况    >> true
plans为一维数组的情况 >> false
plans为空数组的情况   >> false
plans为空字符串的情况 >> false
*/

global $tester;
$tester->app->loadConfig('execution');

$project = new Project();

$plans  = array('1' => array('4', '50', '70'), '2' => array('23', '34', '20'));
$plans1 = array('1', '2');
$plans2 = array();
$plans3 = '';

r($project->update(20, $plans))          && p('')             && e(true);          // 正常更新项目的情况
r($project->update(20, $plans1))         && p('')             && e(false);         // plans为一维数组的情况
r($project->update(20, $plans2))         && p('')             && e(false);         // plans为空数组的情况
r($project->update(20, $plans3))         && p('')             && e(false);         // plans为空字符串的情况
