#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getObjectForPromptById();
timeout=0
cid=15042

- 步骤1：story模块正常情况，返回数组包含两个元素 @2
- 步骤2：task模块正常情况，返回数组包含两个元素 @2
- 步骤3：不存在的prompt ID @0
- 步骤4：不存在的object ID @0
- 步骤5：空参数测试 @0
- 步骤6：product模块测试，返回数组包含两个元素 @2
- 步骤7：bug模块正常情况 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$aiTest = new aiModelTest();

r($aiTest->getObjectForPromptByIdTest(1, 1))   && p() && e('2'); // 步骤1：story模块正常情况，返回数组包含两个元素
r($aiTest->getObjectForPromptByIdTest(3, 1))   && p() && e('2'); // 步骤2：task模块正常情况，返回数组包含两个元素
r($aiTest->getObjectForPromptByIdTest(99, 1))  && p() && e('0'); // 步骤3：不存在的prompt ID
r($aiTest->getObjectForPromptByIdTest(1, 999)) && p() && e('0'); // 步骤4：不存在的object ID
r($aiTest->getObjectForPromptByIdTest('', '')) && p() && e('0'); // 步骤5：空参数测试
r($aiTest->getObjectForPromptByIdTest(7, 1))   && p() && e('2'); // 步骤6：product模块测试，返回数组包含两个元素
r($aiTest->getObjectForPromptByIdTest(5, 1))   && p() && e('2'); // 步骤7：bug模块正常情况
