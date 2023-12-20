#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getNoMergedSprintCount();
timeout=0
cid=1

- 获取没有项目集的产品数量 @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$project = zdTable('project');
$project->project->range('0');
$project->type->range('sprint');
$project->vision->range('rnd');
$project->gen(10);

$upgrade = new upgradeTest();
r($upgrade->getNoMergedSprintCountTest()) && p() && e('10');  //获取没有项目集的产品数量