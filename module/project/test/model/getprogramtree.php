#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('project')->gen(20);
su('admin');

/**

title=测试 projectModel::getProgramTree;
timeout=0
cid=1

- 查询第一个项目集名称第0条的name属性 @项目集1
- 查询项目集数量 @9

*/

global $tester;
$tester->loadModel('project');

$programs = $tester->project->getProgramTree('all');
r($programs)        && p('0:name') && e('项目集1'); // 查询第一个项目集名称
r(count($programs)) && p()         && e('9');       // 查询项目集数量
