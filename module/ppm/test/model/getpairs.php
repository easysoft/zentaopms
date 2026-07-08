#!/usr/bin/env php
<?php

/**

title=测试 mrModel::getPairs();
timeout=0
cid=17253

- 测试步骤1：正常获取有效仓库的MR键值对
 - 属性1 @Test MR 1
 - 属性2 @Test MR 2
 - 属性5 @Test MR 5
- 测试步骤2：测试不存在的仓库ID @0
- 测试步骤3：测试仓库ID为0的边界情况 @0
- 测试步骤4：测试仓库ID为负数的异常输入 @0
- 测试步骤5：测试没有有效MR记录的仓库 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$mr = zenData('mr');
$mr->id->range('1-5');
$mr->title->range('Test MR 1,Test MR 2,Test MR 3,Deleted MR,Test MR 5');
$mr->repoID->range('1,1,2,1,1');
$mr->deleted->range('0,0,0,1,0');
$mr->sourceProject->range('project1,project2,project3,project4,project5');
$mr->targetProject->range('project1,project2,project3,project4,project5');
$mr->gen(5);

su('admin');

$mrTest = new mrModelTest();

r($mrTest->getPairsTest(1)) && p('1,2,5') && e('Test MR 1,Test MR 2,Test MR 5'); // 测试步骤1：正常获取有效仓库的MR键值对
r($mrTest->getPairsTest(999)) && p() && e('0');                                  // 测试步骤2：测试不存在的仓库ID
r($mrTest->getPairsTest(0)) && p() && e('0');                                    // 测试步骤3：测试仓库ID为0的边界情况
r($mrTest->getPairsTest(-1)) && p() && e('0');                                   // 测试步骤4：测试仓库ID为负数的异常输入
r($mrTest->getPairsTest(3)) && p() && e('0');                                    // 测试步骤5：测试没有有效MR记录的仓库