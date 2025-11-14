#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getDataFromUploadImages();
timeout=0
cid=18682

- 步骤1:无session数据时返回默认空需求数组数量 @10
- 步骤2:产品ID相同时保留session数据第0条的title属性 @图片需求1
- 步骤3:产品ID不同时清除session数据第0条的title属性 @~~
- 步骤4:session包含多个图片文件时返回数组数量 @3
- 步骤5:session包含图片时返回需求的uploadImage字段第0条的uploadImage属性 @test_image1.jpg
- 步骤6:设置moduleID时返回第一个需求的module字段第0条的module属性 @5
- 步骤7:设置planID时返回第一个需求的plan字段第0条的plan属性 @10

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$storyZenTest = new storyZenTest();

// 4. 准备测试数据
$emptySession = array();
$singleImageSession = array(
    'test_image1.jpg' => array('title' => '图片需求1', 'file' => 'test_image1.jpg')
);
$multiImageSession = array(
    'test_image1.jpg' => array('title' => '图片需求1', 'file' => 'test_image1.jpg'),
    'test_image2.jpg' => array('title' => '图片需求2', 'file' => 'test_image2.jpg'),
    'test_image3.jpg' => array('title' => '图片需求3', 'file' => 'test_image3.jpg')
);

// 5. 测试步骤 - 必须包含至少5个测试步骤
r(count($storyZenTest->getDataFromUploadImagesTest(1, 0, 0, $emptySession, 0))) && p() && e('10'); // 步骤1:无session数据时返回默认空需求数组数量
r($storyZenTest->getDataFromUploadImagesTest(1, 0, 0, $singleImageSession, 1)) && p('0:title') && e('图片需求1'); // 步骤2:产品ID相同时保留session数据
r($storyZenTest->getDataFromUploadImagesTest(2, 0, 0, $singleImageSession, 1)) && p('0:title') && e('~~'); // 步骤3:产品ID不同时清除session数据
r(count($storyZenTest->getDataFromUploadImagesTest(1, 0, 0, $multiImageSession, 1))) && p() && e('3'); // 步骤4:session包含多个图片文件时返回数组数量
r($storyZenTest->getDataFromUploadImagesTest(1, 0, 0, $singleImageSession, 1)) && p('0:uploadImage') && e('test_image1.jpg'); // 步骤5:session包含图片时返回需求的uploadImage字段
r($storyZenTest->getDataFromUploadImagesTest(1, 5, 0, $emptySession, 1)) && p('0:module') && e('5'); // 步骤6:设置moduleID时返回第一个需求的module字段
r($storyZenTest->getDataFromUploadImagesTest(1, 0, 10, $emptySession, 1)) && p('0:plan') && e('10'); // 步骤7:设置planID时返回第一个需求的plan字段