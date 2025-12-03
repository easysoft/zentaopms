#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processToStoryActionExtra();
timeout=0
cid=0

- 测试普通产品需求1(ID为1) @1
- 测试普通产品需求2(ID为2) @1
- 测试普通产品需求3(ID为3) @1
- 测试影子产品需求(ID为6) @1
- 测试影子产品需求(ID为7) @1
- 测试需求不存在(ID为999) @1
- 测试多个产品ID(ID为3) @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('product')->loadYaml('processtostoryactionextra/product', false, 2)->gen(5);
zenData('story')->loadYaml('processtostoryactionextra/story', false, 2)->gen(10);
zenData('projectstory')->loadYaml('processtostoryactionextra/projectstory', false, 2)->gen(5);

su('admin');

$actionTest = new actionTaoTest();

r(strpos($actionTest->processToStoryActionExtraTest(1, '1')->extra, '#1') !== false && strpos($actionTest->processToStoryActionExtraTest(1, '1')->extra, '用户需求1') !== false) && p() && e('1'); // 测试普通产品需求1(ID为1)
r(strpos($actionTest->processToStoryActionExtraTest(2, '1')->extra, '#2') !== false && strpos($actionTest->processToStoryActionExtraTest(2, '1')->extra, '软件需求2') !== false) && p() && e('1'); // 测试普通产品需求2(ID为2)
r(strpos($actionTest->processToStoryActionExtraTest(3, '1')->extra, '#3') !== false && strpos($actionTest->processToStoryActionExtraTest(3, '1')->extra, '用户需求3') !== false) && p() && e('1'); // 测试普通产品需求3(ID为3)
r(strpos($actionTest->processToStoryActionExtraTest(6, '3')->extra, '#6') !== false) && p() && e('1'); // 测试影子产品需求(ID为6)
r(strpos($actionTest->processToStoryActionExtraTest(7, '3')->extra, '#7') !== false) && p() && e('1'); // 测试影子产品需求(ID为7)
r($actionTest->processToStoryActionExtraTest(999, '1')->extra == '999') && p() && e('1'); // 测试需求不存在(ID为999)
r(strpos($actionTest->processToStoryActionExtraTest(3, ',1,')->extra, '#3') !== false) && p() && e('1'); // 测试多个产品ID(ID为3)