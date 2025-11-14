#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getEstimateInfo();
timeout=0
cid=18528

- 执行storyTest模块的getEstimateInfoTest方法，参数是1, 1  @0
- 执行storyTest模块的getEstimateInfoTest方法，参数是2, 0
 - 属性story @2
 - 属性round @1
 - 属性average @1.5
- 执行storyTest模块的getEstimateInfoTest方法，参数是999, 1  @0
- 执行storyTest模块的getEstimateInfoTest方法，参数是1, 99  @0
- 执行storyTest模块的getEstimateInfoTest方法，参数是3, 2  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

zenData('storyestimate')->gen(10);

su('admin');

$storyTest = new storyTest();

r($storyTest->getEstimateInfoTest(1, 1)) && p() && e('0');
r($storyTest->getEstimateInfoTest(2, 0)) && p('story,round,average') && e('2,1,1.5');
r($storyTest->getEstimateInfoTest(999, 1)) && p() && e('0');
r($storyTest->getEstimateInfoTest(1, 99)) && p() && e('0');
r($storyTest->getEstimateInfoTest(3, 2)) && p() && e('0');