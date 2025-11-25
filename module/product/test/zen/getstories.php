#!/usr/bin/env php
<?php

/**

title=测试 productZen::getStories();
timeout=0
cid=17595

- 步骤1:获取产品1的所有需求属性count @10
- 步骤2:获取产品1的story类型需求属性firstType @story
- 步骤3:获取产品1的requirement类型需求当没有数据属性count @0
- 步骤4:获取产品2的所有需求按id升序排列属性firstId @11
- 步骤5:获取不存在产品的需求属性count @0
- 步骤6:获取项目11关联产品的需求属性count @5
- 步骤7:获取产品3的所有需求并按id升序排列属性firstId @16

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('product')->loadYaml('getstories_product', false, 2)->gen(5);
zendata('story')->loadYaml('getstories_story', false, 2)->gen(20);
zendata('project')->loadYaml('getstories_project', false, 2)->gen(3);
zendata('projectproduct')->loadYaml('getstories_projectproduct', false, 2)->gen(3);
zendata('projectstory')->loadYaml('getstories_projectstory', false, 2)->gen(10);

su('admin');

$productTest = new productZenTest();

r($productTest->getStoriesTest(0, 1, '', 0, 0, 'all', 'allstory', 'id_desc', null)) && p('count') && e('10'); // 步骤1:获取产品1的所有需求
r($productTest->getStoriesTest(0, 1, '', 0, 0, 'story', 'allstory', 'id_desc', null)) && p('firstType') && e('story'); // 步骤2:获取产品1的story类型需求
r($productTest->getStoriesTest(0, 1, '', 0, 0, 'requirement', 'allstory', 'id_desc', null)) && p('count') && e('0'); // 步骤3:获取产品1的requirement类型需求当没有数据
r($productTest->getStoriesTest(0, 2, '', 0, 0, 'all', 'allstory', 'id_asc', null)) && p('firstId') && e('11'); // 步骤4:获取产品2的所有需求按id升序排列
r($productTest->getStoriesTest(0, 999, '', 0, 0, 'all', 'allstory', 'id_desc', null)) && p('count') && e('0'); // 步骤5:获取不存在产品的需求
r($productTest->getStoriesTest(11, 1, '', 0, 0, 'all', 'allstory', 'id_desc', null)) && p('count') && e('5'); // 步骤6:获取项目11关联产品的需求
r($productTest->getStoriesTest(0, 3, '', 0, 0, 'all', 'allstory', 'id_asc', null)) && p('firstId') && e('16'); // 步骤7:获取产品3的所有需求并按id升序排列