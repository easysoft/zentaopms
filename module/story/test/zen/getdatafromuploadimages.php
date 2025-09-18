#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getDataFromUploadImages();
timeout=0
cid=0

- 步骤1：无session图片文件，返回默认批量创建模板10个 @10
- 步骤2：有session图片文件，返回2个故事 @2
- 步骤3：切换产品ID，清除session后返回10个默认模板 @10
- 步骤4：边界值测试productID为0，返回10个默认模板 @10
- 步骤5：使用默认参数moduleID和planID为0，检查第一个故事的module字段第0条的module属性 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 4. 强制要求：必须包含至少5个测试步骤
r(count($storyTest->getDataFromUploadImagesTest(1, 1, 1, array(), ''))) && p() && e('10'); // 步骤1：无session图片文件，返回默认批量创建模板10个
r(count($storyTest->getDataFromUploadImagesTest(1, 1, 1, array('image1.jpg' => array('title' => 'Test Story 1'), 'image2.jpg' => array('title' => 'Test Story 2')), '1'))) && p() && e('2'); // 步骤2：有session图片文件，返回2个故事
r(count($storyTest->getDataFromUploadImagesTest(2, 1, 1, array(), '1'))) && p() && e('10'); // 步骤3：切换产品ID，清除session后返回10个默认模板
r(count($storyTest->getDataFromUploadImagesTest(0, 1, 1, array(), ''))) && p() && e('10'); // 步骤4：边界值测试productID为0，返回10个默认模板
r($storyTest->getDataFromUploadImagesTest(1)) && p('0:module') && e('0'); // 步骤5：使用默认参数moduleID和planID为0，检查第一个故事的module字段