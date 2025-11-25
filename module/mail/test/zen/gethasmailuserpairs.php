#!/usr/bin/env php
<?php

/**

title=测试 mailZen::getHasMailUserPairs();
timeout=0
cid=17041

- 测试步骤1：获取所有有邮箱的用户键值对 @15
- 测试步骤2：验证admin键存在于结果中 @1
- 测试步骤3：验证user1键存在于结果中 @1
- 测试步骤4：验证user5键存在于结果中 @1
- 测试步骤5：验证user9键存在于结果中 @1
- 测试步骤6：验证返回结果数量>=10 @1
- 测试步骤7：验证返回结果数量大于0 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mailzen.unittest.class.php';

zendata('user')->gen(15);

su('admin');

$mailZenTest = new mailZenTest();

r(count($mailZenTest->getHasMailUserPairsZenTest())) && p() && e('15'); // 测试步骤1：获取所有有邮箱的用户键值对
r(array_key_exists('admin', $mailZenTest->getHasMailUserPairsZenTest())) && p() && e('1'); // 测试步骤2：验证admin键存在于结果中
r(array_key_exists('user1', $mailZenTest->getHasMailUserPairsZenTest())) && p() && e('1'); // 测试步骤3：验证user1键存在于结果中
r(array_key_exists('user5', $mailZenTest->getHasMailUserPairsZenTest())) && p() && e('1'); // 测试步骤4：验证user5键存在于结果中
r(array_key_exists('user9', $mailZenTest->getHasMailUserPairsZenTest())) && p() && e('1'); // 测试步骤5：验证user9键存在于结果中
r(count($mailZenTest->getHasMailUserPairsZenTest()) >= 10) && p() && e('1'); // 测试步骤6：验证返回结果数量>=10
r(count($mailZenTest->getHasMailUserPairsZenTest()) > 0) && p() && e('1'); // 测试步骤7：验证返回结果数量大于0