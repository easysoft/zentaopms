#!/usr/bin/env php
<?php

/**

title=测试 storyTao::checkCanSubdivide();
timeout=0
cid=18610

- 执行storyTest模块的checkCanSubdivideTest方法，参数是$storyNormal, false  @1
- 执行storyTest模块的checkCanSubdivideTest方法，参数是$storyRequirement, false  @1
- 执行storyTest模块的checkCanSubdivideTest方法，参数是$storyReviewing, false  @0
- 执行storyTest模块的checkCanSubdivideTest方法，参数是$storyClosed, false  @0
- 执行storyTest模块的checkCanSubdivideTest方法，参数是$storyParent, false  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

su('admin');

$storyTest = new storyTest();

// 创建测试用的story对象
$storyNormal = new stdclass();
$storyNormal->type = 'story';
$storyNormal->status = 'active';
$storyNormal->stage = 'wait';
$storyNormal->isParent = '0';

$storyRequirement = new stdclass();
$storyRequirement->type = 'requirement';
$storyRequirement->status = 'active';
$storyRequirement->stage = 'wait';
$storyRequirement->isParent = '0';

$storyReviewing = new stdclass();
$storyReviewing->type = 'story';
$storyReviewing->status = 'reviewing';
$storyReviewing->stage = 'wait';
$storyReviewing->isParent = '0';

$storyClosed = new stdclass();
$storyClosed->type = 'story';
$storyClosed->status = 'closed';
$storyClosed->stage = 'wait';
$storyClosed->isParent = '0';

$storyParent = new stdclass();
$storyParent->type = 'story';
$storyParent->status = 'active';
$storyParent->stage = 'developing';
$storyParent->isParent = '1';

r($storyTest->checkCanSubdivideTest($storyNormal, false)) && p() && e('1');
r($storyTest->checkCanSubdivideTest($storyRequirement, false)) && p() && e('1');
r($storyTest->checkCanSubdivideTest($storyReviewing, false)) && p() && e('0');
r($storyTest->checkCanSubdivideTest($storyClosed, false)) && p() && e('0');
r($storyTest->checkCanSubdivideTest($storyParent, false)) && p() && e('1');