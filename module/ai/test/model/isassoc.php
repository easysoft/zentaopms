#!/usr/bin/env php
<?php

/**

title=测试 aiModel::isAssoc();
timeout=0
cid=15054

- 步骤1：索引数组 @0
- 步骤2：关联数组 @1
- 步骤3：空数组 @0
- 步骤4：混合键数组 @1
- 步骤5：非连续数字键 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$aiTest = new aiTest();

r($aiTest->isAssocTest(array(0, 1, 2))) && p() && e('0');                            // 步骤1：索引数组
r($aiTest->isAssocTest(array('name' => 'test', 'id' => 1))) && p() && e('1');        // 步骤2：关联数组
r($aiTest->isAssocTest(array())) && p() && e('0');                                   // 步骤3：空数组
r($aiTest->isAssocTest(array(0 => 'a', 'key' => 'b'))) && p() && e('1');            // 步骤4：混合键数组
r($aiTest->isAssocTest(array(1 => 'first', 3 => 'third'))) && p() && e('1');        // 步骤5：非连续数字键