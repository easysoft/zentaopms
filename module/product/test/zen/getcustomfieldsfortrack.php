#!/usr/bin/env php
<?php

/**

title=测试 productZen::getCustomFieldsForTrack();
timeout=0
cid=17578

- 测试步骤1:epic类型返回list数组 @1
- 测试步骤2:epic类型返回show数组 @1
- 测试步骤3:requirement类型不包含requirement字段 @0
- 测试步骤4:story类型不包含requirement和story字段 @0
- 测试步骤5:epic类型包含所有字段 @9
- 测试步骤6:requirement类型list数组包含8个字段 @8
- 测试步骤7:story类型list数组包含7个字段 @7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 3) . '/control.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->gen(10);
su('admin');

$productTest = new productZenTest();

r(isset($productTest->getCustomFieldsForTrackTest('epic')['list'])) && p() && e('1'); // 测试步骤1:epic类型返回list数组
r(isset($productTest->getCustomFieldsForTrackTest('epic')['show'])) && p() && e('1'); // 测试步骤2:epic类型返回show数组
r(isset($productTest->getCustomFieldsForTrackTest('requirement')['list']['requirement'])) && p() && e('0'); // 测试步骤3:requirement类型不包含requirement字段
r(isset($productTest->getCustomFieldsForTrackTest('story')['list']['requirement']) || isset($productTest->getCustomFieldsForTrackTest('story')['list']['story'])) && p() && e('0'); // 测试步骤4:story类型不包含requirement和story字段
r(count($productTest->getCustomFieldsForTrackTest('epic')['list'])) && p() && e('9'); // 测试步骤5:epic类型包含所有字段
r(count($productTest->getCustomFieldsForTrackTest('requirement')['list'])) && p() && e('8'); // 测试步骤6:requirement类型list数组包含8个字段
r(count($productTest->getCustomFieldsForTrackTest('story')['list'])) && p() && e('7'); // 测试步骤7:story类型list数组包含7个字段