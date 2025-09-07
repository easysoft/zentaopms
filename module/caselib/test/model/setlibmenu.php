#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

zenData('testsuite')->gen(5);
zenData('user')->gen(1);

su('admin');

/**

title=测试 caselibModel::setLibMenu();
timeout=0
cid=0

- 步骤1：正常情况 @rue
- 步骤2：空列表但有效libID @rue
- 步骤3：空列表和无效libID @rue
- 步骤4：libID不在列表中 @rue
- 步骤5：libID为0 @rue

*/

$caselibTest = new caselibTest();

r($caselibTest->setLibMenuTest(array(1 => '用例库1', 2 => '用例库2'), 1)) && p() && e(true); // 步骤1：正常情况
r($caselibTest->setLibMenuTest(array(), 1)) && p() && e(true); // 步骤2：空列表但有效libID  
r($caselibTest->setLibMenuTest(array(), 999)) && p() && e(true); // 步骤3：空列表和无效libID
r($caselibTest->setLibMenuTest(array(1 => '用例库1', 2 => '用例库2'), 999)) && p() && e(true); // 步骤4：libID不在列表中
r($caselibTest->setLibMenuTest(array(), 0)) && p() && e(true); // 步骤5：libID为0