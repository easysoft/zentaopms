#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

/**

title=测试 programModel::getTopByID();
timeout=0
cid=17701

- 获取项目集1最上级的项目集id @1
- 获取项目集2最上级的项目集id @1
- 获取项目集3最上级的项目集id @4
- 获取项目集4最上级的项目集id @4
- 获取项目集1000最上级的项目集id @0

*/
zenData('project')->loadYaml('program')->gen(10);

global $tester;
$tester->loadModel('program');
$top1    = $tester->program->getTopByID(1);
$top2    = $tester->program->getTopByID(2);
$top3    = $tester->program->getTopByID(4);
$top4    = $tester->program->getTopByID(4);
$top1000 = $tester->program->getTopByID(1000);

r($top1)    && p() && e('1'); //获取项目集1最上级的项目集id
r($top2)    && p() && e('1'); //获取项目集2最上级的项目集id
r($top3)    && p() && e('4'); //获取项目集3最上级的项目集id
r($top4)    && p() && e('4'); //获取项目集4最上级的项目集id
r($top1000) && p() && e('0'); //获取项目集1000最上级的项目集id