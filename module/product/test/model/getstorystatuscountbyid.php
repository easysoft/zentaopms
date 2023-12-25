#!/usr/bin/env php
<?php

/**

title=productModel->getStoryStatusCountByID();
cid=0

- 测试获取空产品ID下的需求数据属性draft @0
- 测试获取产品1下的需求数据属性draft @1
- 测试获取不存在产品下的需求数据属性draft @0
- 测试获取空产品ID下的用需数据属性draft @0
- 测试获取产品1下的用需数据属性draft @0
- 测试获取不存在产品下的用需数据属性draft @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('story')->config('story')->gen(100);
zdTable('user')->gen(5);
su('admin');

$productIdList = array(0, 1, 100);

$storyTypeList = array('story', 'requirement');

global $tester;
$tester->loadModel('product');
r($tester->product->getStoryStatusCountByID($productIdList[0], $storyTypeList[0])) && p('draft') && e('0'); // 测试获取空产品ID下的需求数据
r($tester->product->getStoryStatusCountByID($productIdList[1], $storyTypeList[0])) && p('draft') && e('1'); // 测试获取产品1下的需求数据
r($tester->product->getStoryStatusCountByID($productIdList[2], $storyTypeList[0])) && p('draft') && e('0'); // 测试获取不存在产品下的需求数据
r($tester->product->getStoryStatusCountByID($productIdList[0], $storyTypeList[1])) && p('draft') && e('0'); // 测试获取空产品ID下的用需数据
r($tester->product->getStoryStatusCountByID($productIdList[1], $storyTypeList[1])) && p('draft') && e('0'); // 测试获取产品1下的用需数据
r($tester->product->getStoryStatusCountByID($productIdList[2], $storyTypeList[1])) && p('draft') && e('0'); // 测试获取不存在产品下的用需数据
