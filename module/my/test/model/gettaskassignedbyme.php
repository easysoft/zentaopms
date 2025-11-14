#!/usr/bin/env php
<?php

/**

title=测试 myModel::getTaskAssignedByMe();
timeout=0
cid=17302

- 步骤1：空ID列表 @0
- 步骤2：有效ID列表 @0
- 步骤3：优先级排序 @0
- 步骤4：项目名排序 @0
- 步骤5：多个ID过滤 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('task')->gen('10');
zenData('user')->gen('1');

su('admin');

$myTest = new myTest();

r($myTest->getTaskAssignedByMeTest(null, 'id_desc', array())) && p() && e('0'); // 步骤1：空ID列表
r($myTest->getTaskAssignedByMeTest(null, 'id_desc', array(1,2,3))) && p() && e('0'); // 步骤2：有效ID列表
r($myTest->getTaskAssignedByMeTest(null, 'pri_desc', array(1,2,3))) && p() && e('0'); // 步骤3：优先级排序
r($myTest->getTaskAssignedByMeTest(null, 'projectName_desc', array(1,2,3))) && p() && e('0'); // 步骤4：项目名排序
r($myTest->getTaskAssignedByMeTest(null, 'id_desc', array(1,2,3,4,5))) && p() && e('0'); // 步骤5：多个ID过滤