#!/usr/bin/env php
<?php

/**

title=测试 storyModel::batchChangeParent();
cid=0

- 测试步骤1：正常批量更改父需求 >> 期望正常结果
- 测试步骤2：空的故事ID列表输入 >> 期望无操作结果
- 测试步骤3：无效的父需求ID >> 期望正常处理结果
- 测试步骤4：自身作为父需求的错误情况 >> 期望错误处理结果
- 测试步骤5：正常的需求层级变更 >> 期望正常结果

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

zenData('user')->gen(5);
zenData('product')->gen(5);
zenData('story')->gen(10);
zenData('storygrade')->gen(6);
zenData('storyspec')->gen(10);

su('admin');

$storyTest = new storyTest();

r($storyTest->batchChangeParentTest('6,7', 1, 'story')) && p() && e('');
r($storyTest->batchChangeParentTest('', 1, 'story')) && p() && e('~~');
r($storyTest->batchChangeParentTest('8,9', 999, 'story')) && p() && e('');
r($storyTest->batchChangeParentTest('1', 1, 'story')) && p() && e('#1需求的父需求不能为其本身或其子需求，本次修改已将其忽略。');
r($storyTest->batchChangeParentTest('3,4', 2, 'story')) && p() && e('');