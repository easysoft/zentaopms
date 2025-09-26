#!/usr/bin/env php
<?php

/**

title=测试 mailTao::getMailContent();
cid=0

- 测试步骤1：传入空objectType参数情况 >> 期望返回空字符串
- 测试步骤2：传入空object参数情况 >> 期望返回空字符串
- 测试步骤3：传入空action参数情况 >> 期望返回空字符串
- 测试步骤4：传入mr类型参数情况 >> 期望返回空字符串
- 测试步骤5：传入无效objectType情况 >> 期望返回空字符串

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

su('admin');

$mail = new mailTest();

/* Create mock objects for testing */
$mockStory = new stdClass();
$mockStory->id = 1;
$mockStory->title = '测试需求';

$mockTask = new stdClass();
$mockTask->id = 1;
$mockTask->name = '测试任务';

$mockAction = new stdClass();
$mockAction->id = 1;

r($mail->getMailContentTest('')) && p() && e(''); // 测试步骤1：传入空objectType参数情况
r($mail->getMailContentTest('story', null)) && p() && e(''); // 测试步骤2：传入空object参数情况
r($mail->getMailContentTest('story', $mockStory, null)) && p() && e(''); // 测试步骤3：传入空action参数情况
r($mail->getMailContentTest('mr', $mockStory, $mockAction)) && p() && e(''); // 测试步骤4：传入mr类型参数情况
r($mail->getMailContentTest('invalid', $mockStory, $mockAction)) && p() && e(''); // 测试步骤5：传入无效objectType情况