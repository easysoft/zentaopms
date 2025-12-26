#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('project')->gen(4);

/**

title=测试 projectModel->getListByCurrentUser();
timeout=0
cid=17832

- 执行projectModel模块的getListByCurrentUser方法  @0
- 执行projectModel模块的getListByCurrentUser方法  @1
- 执行projectModel模块的getListByCurrentUser方法
 - 第1条的id属性 @1
 - 第1条的name属性 @项目1
- 执行projectModel模块的getListByCurrentUser方法
 - 第1条的budget属性 @900000.00
 - 第1条的budgetUnit属性 @CNY
- 执行projectModel模块的getListByCurrentUser方法
 - 第1条的auth属性 @extend
 - 第1条的code属性 @program1

*/

global $tester;
$projectModel = $tester->loadModel('project');

$tester->app->user->admin    = true;
$tester->app->config->vision = 'lite';

r(count($projectModel->getListByCurrentUser())) && p() && e('0');

$tester->app->config->vision = 'rnd';
r(count($projectModel->getListByCurrentUser())) && p()                      && e('1');
r($projectModel->getListByCurrentUser())        && p('1:id,name')           && e('1,项目1');
r($projectModel->getListByCurrentUser())        && p('1:budget,budgetUnit') && e('900000.00,CNY');
r($projectModel->getListByCurrentUser())        && p('1:auth,code')         && e('extend,program1');
