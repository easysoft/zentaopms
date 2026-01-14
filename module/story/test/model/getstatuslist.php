#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getStatusList();
timeout=0
cid=18555

- 步骤1：获取noclosed状态列表
 - 属性1 @draft
 - 属性2 @reviewing
 - 属性3 @active
 - 属性4 @changing
- 步骤2：获取active状态列表 @active
- 步骤3：获取空字符串状态列表 @0
- 步骤4：获取无效状态列表 @0
- 步骤5：获取closed状态列表 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$storyTest = new storyModelTest();

r($storyTest->getStatusListTest('noclosed')) && p('1,2,3,4') && e('draft,reviewing,active,changing'); // 步骤1：获取noclosed状态列表
r($storyTest->getStatusListTest('active')) && p() && e('active'); // 步骤2：获取active状态列表
r($storyTest->getStatusListTest('')) && p() && e('0'); // 步骤3：获取空字符串状态列表
r($storyTest->getStatusListTest('invalid')) && p() && e('0'); // 步骤4：获取无效状态列表
r($storyTest->getStatusListTest('closed')) && p() && e('0'); // 步骤5：获取closed状态列表