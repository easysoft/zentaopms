#!/usr/bin/env php
<?php

/**

title=测试 productZen::getActiveStoryTypeForTrack();
timeout=0
cid=0

- 步骤1：无项目ID和产品ID的默认情况，应返回3个类型 @3
- 步骤2：非projectstory模块测试，应返回3个类型 @3
- 步骤3：不存在的项目ID测试，应返回3个类型 @3
- 步骤4：测试epic键是否存在 @1
- 步骤5：测试story键是否存在 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->loadYaml('story_getactivestorytypefortrack', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($productTest->getActiveStoryTypeForTrackTest(0, 0))) && p() && e('3'); // 步骤1：无项目ID和产品ID的默认情况，应返回3个类型
r(count($productTest->getActiveStoryTypeForTrackTest(0, 1))) && p() && e('3'); // 步骤2：非projectstory模块测试，应返回3个类型
r(count($productTest->getActiveStoryTypeForTrackTest(999, 1))) && p() && e('3'); // 步骤3：不存在的项目ID测试，应返回3个类型
r(isset($productTest->getActiveStoryTypeForTrackTest(1, 1)['epic'])) && p() && e('1'); // 步骤4：测试epic键是否存在
r(isset($productTest->getActiveStoryTypeForTrackTest(2, 2)['story'])) && p() && e('1'); // 步骤5：测试story键是否存在