#!/usr/bin/env php
<?php

/**

title=测试 productZen::getModuleTree();
timeout=0
cid=17589

- 测试正常产品获取模块树结果是否为数组 @1
- 测试带分支参数的模块树结果是否为数组 @1
- 测试需求类型为requirement的模块树结果是否为数组 @1
- 测试需求类型为epic的模块树结果是否为数组 @1
- 测试空browseType时使用默认unclosed并返回数组 @1
- 测试项目需求模块树返回数组 @1
- 测试projectstory模块的项目需求树返回数组 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->gen(10);
zenData('project')->gen(10);
zenData('module')->gen(20);
zenData('story')->gen(50);

su('admin');

$productTest = new productZenTest();

r(is_array($productTest->getModuleTreeTest(0, 1, '', 0, 'story', 'unclosed'))) && p() && e('1'); // 测试正常产品获取模块树结果是否为数组
r(is_array($productTest->getModuleTreeTest(0, 1, 'all', 0, 'story', 'unclosed'))) && p() && e('1'); // 测试带分支参数的模块树结果是否为数组
r(is_array($productTest->getModuleTreeTest(0, 1, '', 0, 'requirement', 'unclosed'))) && p() && e('1'); // 测试需求类型为requirement的模块树结果是否为数组
r(is_array($productTest->getModuleTreeTest(0, 1, '', 0, 'epic', 'unclosed'))) && p() && e('1'); // 测试需求类型为epic的模块树结果是否为数组
r(is_array($productTest->getModuleTreeTest(0, 1, '', 0, 'story', ''))) && p() && e('1'); // 测试空browseType时使用默认unclosed并返回数组
r(is_array($productTest->getModuleTreeTest(1, 0, '', 0, 'story', 'unclosed'))) && p() && e('1'); // 测试项目需求模块树返回数组
r(is_array($productTest->getModuleTreeTest(1, 1, '', 0, 'story', 'unclosed'))) && p() && e('1'); // 测试projectstory模块的项目需求树返回数组