#!/usr/bin/env php
<?php

/**

title=测试 aiModel::executePrompt();
timeout=0
cid=0

- 步骤1：空prompt参数 @-1
- 步骤2：无效prompt ID @-1
- 步骤3：空object参数 @-2
- 步骤4：无效object ID @-2
- 步骤5：零值参数测试 @-1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

zenData('ai_prompt')->gen(5);
zenData('ai_model')->gen(3);  
zenData('story')->gen(3);
zenData('storyspec')->gen(3);

su('admin');

$aiTest = new aiTest();

r($aiTest->executePromptTest(null, 1)) && p() && e(-1);        // 步骤1：空prompt参数  
r($aiTest->executePromptTest(999, 1)) && p() && e(-1);         // 步骤2：无效prompt ID
r($aiTest->executePromptTest(1, null)) && p() && e(-2);        // 步骤3：空object参数
r($aiTest->executePromptTest(1, 999)) && p() && e(-2);         // 步骤4：无效object ID
r($aiTest->executePromptTest(0, 0)) && p() && e(-1);           // 步骤5：零值参数测试