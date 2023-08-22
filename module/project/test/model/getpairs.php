#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zdTable('project')->gen(50);
su('admin');

/**

title=测试 projectModel->getPairs();
timeout=0
cid=1

- 查找管理员可查看的所有项目数量 @40

- 获取ID为50的项目的名称属性50 @项目50

*/

global $tester;
$tester->loadModel('project');

r(count($tester->project->getPairs())) && p()     && e('40');     // 查找管理员可查看的所有项目数量
r($tester->project->getPairs())        && p('50') && e('项目50'); // 获取ID为50的项目的名称