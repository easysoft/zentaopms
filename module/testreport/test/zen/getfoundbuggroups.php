#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::getFoundBugGroups();
timeout=0
cid=19134

- 步骤1：空数组输入 @8
- 步骤2：单个bug分组返回数组长度 @8
- 步骤3：多个bug分组返回数组长度 @8
- 步骤4：三个bug分组返回数组长度 @8
- 步骤5：五个bug分组返回数组长度 @8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

zenData('bug')->loadYaml('bug_getfoundbuggroups', false, 2)->gen(10);

su('admin');

$testreportTest = new testreportTest();

r($testreportTest->getFoundBugGroupsTest(array())) && p() && e('8'); // 步骤1：空数组输入
r($testreportTest->getFoundBugGroupsTest(array(1))) && p() && e('8'); // 步骤2：单个bug分组返回数组长度
r($testreportTest->getFoundBugGroupsTest(array(1, 2))) && p() && e('8'); // 步骤3：多个bug分组返回数组长度
r($testreportTest->getFoundBugGroupsTest(array(1, 2, 3))) && p() && e('8'); // 步骤4：三个bug分组返回数组长度
r($testreportTest->getFoundBugGroupsTest(array(1, 2, 3, 4, 5))) && p() && e('8'); // 步骤5：五个bug分组返回数组长度