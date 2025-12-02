#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

zenData('project')->gen(50);
zenData('user')->gen(5);
su('admin');

/**

title=测试 projectModel::setMenu();
timeout=0
cid=17867

- 项目不存在的情况 @迭代
- 敏捷项目 @迭代
- 融合敏捷项目 @迭代
- 看板项目 @项目看板
- 瀑布项目 @阶段
- 融合瀑布项目 @阶段

*/

$projectTester = new projectTest();

r($projectTester->setMenuTest(1))  && p() && e('迭代');     // 项目不存在的情况
r($projectTester->setMenuTest(11)) && p() && e('迭代');     // 敏捷项目
r($projectTester->setMenuTest(14)) && p() && e('迭代');     // 融合敏捷项目
r($projectTester->setMenuTest(13)) && p() && e('项目看板'); // 看板项目
r($projectTester->setMenuTest(12)) && p() && e('阶段');     // 瀑布项目
r($projectTester->setMenuTest(15)) && p() && e('阶段');     // 融合瀑布项目
