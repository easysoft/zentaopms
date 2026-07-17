#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getTestingLink();
timeout=0
cid=25073

- 步骤1：数字objectId生成测试链接 @1
- 步骤2：字符串objectId生成测试链接 @1
- 步骤3：objectId为0时返回false @0
- 步骤4：objectId为空字符串时返回false @0
- 步骤5：objectId为false时返回false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

$prompt     = new stdclass();
$prompt->id = 11;

r($aiTest->getTestingLinkTest($prompt, 22))       && p() && e('1'); // 步骤1：数字objectId生成测试链接
r($aiTest->getTestingLinkTest($prompt, 'ABC123')) && p() && e('1'); // 步骤2：字符串objectId生成测试链接
r($aiTest->getTestingLinkTest($prompt, 0))        && p() && e('0'); // 步骤3：objectId为0时返回false
r($aiTest->getTestingLinkTest($prompt, ''))       && p() && e('0'); // 步骤4：objectId为空字符串时返回false
r($aiTest->getTestingLinkTest($prompt, false))    && p() && e('0'); // 步骤5：objectId为false时返回false
