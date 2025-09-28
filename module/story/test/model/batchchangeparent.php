#!/usr/bin/env php
<?php

/**

title=- 测试步骤4：自身作为父需求的错误情况 @
timeout=0
cid=1

- 测试步骤1：正常批量更改父需求 @
- 测试步骤2：空的故事ID列表输入 @~~
- 测试步骤3：无效的父需求ID @
- 测试步骤4：自身作为父需求的错误情况 @#1需求的父需求不能为其本身或其子需求，本次修改已将其忽略。
- 测试步骤5：正常的需求层级变更 @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('user')->gen(5);
zenData('product')->gen(5);
zenData('story')->gen(10);
zenData('storygrade')->gen(6);
zenData('storyspec')->gen(10);

global $tester;
$tester->loadModel('story');

r($tester->story->batchChangeParent('6,7', 1, 'story')) && p() && e(''); // 测试步骤1：正常批量更改父需求
r($tester->story->batchChangeParent('', 1, 'story')) && p() && e('~~'); // 测试步骤2：空的故事ID列表输入
r($tester->story->batchChangeParent('8,9', 999, 'story')) && p() && e(''); // 测试步骤3：无效的父需求ID
r($tester->story->batchChangeParent('1', 1, 'story')) && p() && e('#1需求的父需求不能为其本身或其子需求，本次修改已将其忽略。'); // 测试步骤4：自身作为父需求的错误情况
r($tester->story->batchChangeParent('3,4', 2, 'story')) && p() && e(''); // 测试步骤5：正常的需求层级变更