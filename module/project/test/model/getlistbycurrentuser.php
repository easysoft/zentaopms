#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->config('project')->gen(4);

/**

title=测试 projectModel->getListByCurrentUser();
timeout=0
cid=1



*/

global $tester;
$projectModel = $tester->loadModel('project');

$tester->app->user->admin    = true;
$tester->app->config->vision = 'lite';

r(count($projectModel->getListByCurrentUser())) && p() && e('0');

$tester->app->config->vision = 'rnd';
r(count($projectModel->getListByCurrentUser())) && p()            && e('1');
r($projectModel->getListByCurrentUser())        && p('1:id,name') && e('1,项目1');
