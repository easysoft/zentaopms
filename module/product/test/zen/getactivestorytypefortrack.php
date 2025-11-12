#!/usr/bin/env php
<?php

/**

title=测试 productZen::getActiveStoryTypeForTrackTest();
timeout=0
cid=0

- 测试步骤1:product模块包含story键 @1
- 测试步骤2:product模块包含requirement键 @1
- 测试步骤3:product模块包含epic键 @1
- 测试步骤4:projectstory模块包含story键 @1
- 测试步骤5:projectstory模块包含requirement键 @1
- 测试步骤6:验证返回数组 @1
- 测试步骤7:验证返回3个类型 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('story')->loadYaml('getactivestorytypefortrack/story', false, 2)->gen(50);
zenData('projectstory')->loadYaml('getactivestorytypefortrack/projectstory', false, 2)->gen(50);
zenData('project')->loadYaml('getactivestorytypefortrack/project', false, 2)->gen(10);
su('admin');

$productTest = new productZenTest();

r(isset($productTest->getActiveStoryTypeForTrackTest(0, 0)['story'])) && p() && e('1'); // 测试步骤1:product模块包含story键
r(isset($productTest->getActiveStoryTypeForTrackTest(0, 0)['requirement'])) && p() && e('1'); // 测试步骤2:product模块包含requirement键
r(isset($productTest->getActiveStoryTypeForTrackTest(0, 0)['epic'])) && p() && e('1'); // 测试步骤3:product模块包含epic键
r(isset($productTest->getActiveStoryTypeForTrackTest(1, 1)['story'])) && p() && e('1'); // 测试步骤4:projectstory模块包含story键
r(isset($productTest->getActiveStoryTypeForTrackTest(1, 1)['requirement'])) && p() && e('1'); // 测试步骤5:projectstory模块包含requirement键
r(is_array($productTest->getActiveStoryTypeForTrackTest(0, 0))) && p() && e('1'); // 测试步骤6:验证返回数组
r(count($productTest->getActiveStoryTypeForTrackTest(0, 0))) && p() && e('3'); // 测试步骤7:验证返回3个类型