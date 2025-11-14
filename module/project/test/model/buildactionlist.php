#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

zenData('project')->loadYaml('project')->gen(7);

/**

title=测试 projectModel->buildActionList();
timeout=0
cid=17801

- 设置敏捷项目的操作按钮 @start
- 设置瀑布项目的操作按钮 @close
- 设置看板项目的操作按钮 @start
- 设置融合敏捷项目的操作按钮 @active
- 设置融合瀑布项目的操作按钮 @close

*/

$projectIdList = range(2, 7);

$projectTester = new projectTest();
r($projectTester->buildActionListObjectTest($projectIdList[0])) && p() && e('start');  // 设置敏捷项目的操作按钮
r($projectTester->buildActionListObjectTest($projectIdList[1])) && p() && e('close');  // 设置瀑布项目的操作按钮
r($projectTester->buildActionListObjectTest($projectIdList[2])) && p() && e('start');  // 设置看板项目的操作按钮
r($projectTester->buildActionListObjectTest($projectIdList[3])) && p() && e('active'); // 设置融合敏捷项目的操作按钮
r($projectTester->buildActionListObjectTest($projectIdList[4])) && p() && e('close');  // 设置融合瀑布项目的操作按钮
