#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$project = zdTable('project')->config('project');
$project->gen(4);

/**

title=测试 projectModel->getListByCurrentUser();
timeout=0
cid=1

- 执行projectModel模块的getListByCurrentUser方法 @0

- 执行projectModel模块的getListByCurrentUser方法 @1

- 执行projectModel模块的getListByCurrentUser方法
 - 第11条的id属性 @11
 - 第11条的name属性 @项目1



*/

global $tester;
$projectModel = $tester->loadModel('project');

$tester->app->user->admin    = true;
$tester->app->config->vision = 'lite';

r(count($projectModel->getListByCurrentUser())) && p() && e('0');

$tester->app->config->vision = 'rnd';
r(count($projectModel->getListByCurrentUser())) && p()             && e('1');
r($projectModel->getListByCurrentUser())        && p('11:id,name') && e('11,项目1');