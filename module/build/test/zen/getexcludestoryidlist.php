#!/usr/bin/env php
<?php

/**

title=测试 buildZen::getExcludeStoryIdList();
timeout=0
cid=0

- 步骤1：正常版本获取排除需求ID列表 @3
- 步骤2：版本无已关联需求情况 @0
- 步骤3：产品无父需求情况 @2
- 步骤4：版本无已关联需求且产品无父需求 @0
- 步骤5：版本已关联需求包含父需求的情况 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('story')->loadYaml('zt_story_getexcludestoryidlist', false, 2)->gen(20);
zenData('build')->loadYaml('zt_build_getexcludestoryidlist', false, 2)->gen(5);

su('admin');

$buildTest = new buildTest();

// 测试数据准备
$build1 = new stdclass();
$build1->id = 1;
$build1->product = 1;
$build1->allStories = '1,2,3';

$build2 = new stdclass();
$build2->id = 2;
$build2->product = 1;
$build2->allStories = '';

$build3 = new stdclass();
$build3->id = 3;
$build3->product = 2;
$build3->allStories = '4,5';

$build4 = new stdclass();
$build4->id = 4;
$build4->product = 3;
$build4->allStories = '';

$build5 = new stdclass();
$build5->id = 5;
$build5->product = 1;
$build5->allStories = '16,17,18';

r($buildTest->getExcludeStoryIdListTest($build1)) && p() && e('3'); // 步骤1：正常版本获取排除需求ID列表
r($buildTest->getExcludeStoryIdListTest($build2)) && p() && e('0'); // 步骤2：版本无已关联需求情况  
r($buildTest->getExcludeStoryIdListTest($build3)) && p() && e('2'); // 步骤3：产品无父需求情况
r($buildTest->getExcludeStoryIdListTest($build4)) && p() && e('0'); // 步骤4：版本无已关联需求且产品无父需求
r($buildTest->getExcludeStoryIdListTest($build5)) && p() && e('3'); // 步骤5：版本已关联需求包含父需求的情况