#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';

zdTable('project')->gen(50);
su('admin');

/**

title=测试 projectModel::setMenu();
cid=1
pid=1

*/

$projectTester = new Project();

r($projectTester->setMenuTest(100)) && p() && e('迭代');     // 项目不存在的情况
r($projectTester->setMenuTest(11))  && p() && e('迭代');     // 敏捷项目
r($projectTester->setMenuTest(14))  && p() && e('迭代');     // 融合敏捷项目
r($projectTester->setMenuTest(13))  && p() && e('项目看板'); // 看板项目
r($projectTester->setMenuTest(12))  && p() && e('阶段');     // 瀑布项目
r($projectTester->setMenuTest(15))  && p() && e('阶段');     // 融合瀑布项目
