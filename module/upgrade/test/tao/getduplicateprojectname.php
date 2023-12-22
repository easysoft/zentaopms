#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getDuplicateProjectNameTest();
timeout=0
cid=1

- 测试重复项目名称的 id 列表 @2,1,

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

su('admin');

$project = zdTable('project');
$project->name->range('项目1{2},项目2,项目集1');
$project->type->range('project{3},program');
$project->gen('3');

$projectIdList = array(1, 2);

$upgrade = new upgradeTest();
r($upgrade->getDuplicateProjectNameTest($projectIdList)) && p('') && e('2,1,'); // 测试重复项目名称的 id 列表