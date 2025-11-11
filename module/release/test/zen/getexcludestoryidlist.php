#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::getExcludeStoryIdList();
timeout=0
cid=0

- 执行releaseTest模块的getExcludeStoryIdListTest方法，参数是$release1
 - 属性1 @1
 - 属性2 @2
 - 属性3 @3
- 执行releaseTest模块的getExcludeStoryIdListTest方法，参数是$release2
 - 属性4 @4
 - 属性5 @5
- 执行$result3 @1
- 执行releaseTest模块的getExcludeStoryIdListTest方法，参数是$release4
 - 属性14 @14
 - 属性15 @15
- 执行$result5 @1
- 执行$result6 @7
- 执行releaseTest模块的getExcludeStoryIdListTest方法，参数是$release7
 - 属性8 @8
 - 属性9 @9
 - 属性10 @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/releasezen.unittest.class.php';

zendata('story')->loadYaml('getexcludestoryidlist/story', false, 2)->gen(20);

su('admin');

$releaseTest = new releaseZenTest();

// 测试步骤1:产品1的发布关联需求1,2,3
$release1 = new stdClass();
$release1->product = 1;
$release1->stories = '1,2,3';
r($releaseTest->getExcludeStoryIdListTest($release1)) && p('1;2;3') && e('1;2;3');

// 测试步骤2:产品1的发布关联需求4,5
$release2 = new stdClass();
$release2->product = 1;
$release2->stories = '4,5';
r($releaseTest->getExcludeStoryIdListTest($release2)) && p('4;5') && e('4;5');

// 测试步骤3:产品1的发布stories字段为空
$release3 = new stdClass();
$release3->product = 1;
$release3->stories = '';
$result3 = $releaseTest->getExcludeStoryIdListTest($release3);
r(is_array($result3)) && p() && e('1');

// 测试步骤4:产品2的发布关联需求14,15
$release4 = new stdClass();
$release4->product = 2;
$release4->stories = '14,15';
r($releaseTest->getExcludeStoryIdListTest($release4)) && p('14;15') && e('14;15');

// 测试步骤5:产品3的发布stories字段为空
$release5 = new stdClass();
$release5->product = 3;
$release5->stories = '';
$result5 = $releaseTest->getExcludeStoryIdListTest($release5);
r(is_array($result5)) && p() && e('1');

// 测试步骤6:测试stories字段包含多个逗号分隔的ID
$release6 = new stdClass();
$release6->product = 1;
$release6->stories = '1,2,3,4,5,6,7';
$result6 = $releaseTest->getExcludeStoryIdListTest($release6);
r(count($result6)) && p() && e('7');

// 测试步骤7:测试stories字段含前后逗号的情况
$release7 = new stdClass();
$release7->product = 1;
$release7->stories = ',8,9,10,';
r($releaseTest->getExcludeStoryIdListTest($release7)) && p('8;9;10') && e('8;9;10');