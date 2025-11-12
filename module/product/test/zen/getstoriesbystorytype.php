#!/usr/bin/env php
<?php

/**

title=测试 productZen::getStoriesByStoryType();
timeout=0
cid=0

- 执行productTest模块的getStoriesByStoryTypeTest方法，参数是1, '', 'all', 'id_desc' 属性count @10
- 执行productTest模块的getStoriesByStoryTypeTest方法，参数是1, '', 'story', 'id_desc'
 - 属性count @5
 - 属性firstType @story
- 执行productTest模块的getStoriesByStoryTypeTest方法，参数是1, '', 'requirement', 'id_desc'
 - 属性count @5
 - 属性firstType @requirement
- 执行productTest模块的getStoriesByStoryTypeTest方法，参数是999, '', 'all', 'id_desc' 属性count @0
- 执行productTest模块的getStoriesByStoryTypeTest方法，参数是1, '', 'story', 'id_desc' 属性firstId @5
- 执行productTest模块的getStoriesByStoryTypeTest方法，参数是1, '', 'story', 'id_asc' 属性firstId @1
- 执行productTest模块的getStoriesByStoryTypeTest方法，参数是1, '', 'epic', 'id_desc' 属性count @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('story')->loadYaml('story', false, 2)->gen(30);
zenData('product')->loadYaml('product', false, 2)->gen(10);
zenData('user')->gen(5);

su('admin');

$productTest = new productZenTest();

r($productTest->getStoriesByStoryTypeTest(1, '', 'all', 'id_desc')) && p('count') && e('10');
r($productTest->getStoriesByStoryTypeTest(1, '', 'story', 'id_desc')) && p('count;firstType') && e('5;story');
r($productTest->getStoriesByStoryTypeTest(1, '', 'requirement', 'id_desc')) && p('count;firstType') && e('5;requirement');
r($productTest->getStoriesByStoryTypeTest(999, '', 'all', 'id_desc')) && p('count') && e('0');
r($productTest->getStoriesByStoryTypeTest(1, '', 'story', 'id_desc')) && p('firstId') && e('5');
r($productTest->getStoriesByStoryTypeTest(1, '', 'story', 'id_asc')) && p('firstId') && e('1');
r($productTest->getStoriesByStoryTypeTest(1, '', 'epic', 'id_desc')) && p('count') && e('0');
