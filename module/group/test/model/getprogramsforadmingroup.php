#!/usr/bin/env php
<?php

/**

title=测试 groupModel::getProgramsForAdminGroup();
timeout=0
cid=16714

- 执行groupTest模块的getProgramsForAdminGroupTest方法 属性1 @项目集1
- 执行groupTest模块的getProgramsForAdminGroupTest方法 属性1 @项目集1
- 执行groupTest模块的getProgramsForAdminGroupTest方法  @0
- 执行groupTest模块的getProgramsForAdminGroupTest方法 属性1 @项目集1
- 执行groupTest模块的getProgramsForAdminGroupTest方法 属性1 @项目集1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('project')->gen(10);

su('admin');

$groupTest = new groupModelTest();

r($groupTest->getProgramsForAdminGroupTest()) && p('1') && e('项目集1');
su('user1');  
r($groupTest->getProgramsForAdminGroupTest()) && p('1') && e('项目集1');
zenData('project')->gen(0);
r($groupTest->getProgramsForAdminGroupTest()) && p() && e('0');
zenData('project')->loadYaml('project')->gen(10);
global $app;
$originalVision = $app->config->vision;
$app->config->vision = 'rnd';
r($groupTest->getProgramsForAdminGroupTest()) && p('1') && e('项目集1');
$app->config->vision = $originalVision;
r($groupTest->getProgramsForAdminGroupTest()) && p('1') && e('项目集1');