#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';

zdTable('project')->gen(50);
su('admin');

/**

title=测试 projectModel::setMenuByModel();
cid=1
pid=1

*/

$projectTester = new Project();

r($projectTester->setMenuByModelTest(''))              && p() && e('迭代');     // 项目不存在的情况
r($projectTester->setMenuByModelTest('error'))         && p() && e('迭代');     // 项目不存在的情况
r($projectTester->setMenuByModelTest('scrum'))         && p() && e('迭代');     // 敏捷项目
r($projectTester->setMenuByModelTest('agileplus'))     && p() && e('迭代');     // 融合敏捷项目
r($projectTester->setMenuByModelTest('waterfall'))     && p() && e('阶段');     // 融合瀑布项目
r($projectTester->setMenuByModelTest('waterfallplus')) && p() && e('阶段');     // 瀑布项目
r($projectTester->setMenuByModelTest('kanban'))        && p() && e('项目看板'); // 看板项目
