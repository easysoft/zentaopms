#!/usr/bin/env php
<?php

/**

title=测试 mailModel::setImages();
timeout=0
cid=17022

- 步骤1：正常图片数组属性processed @1
- 步骤2：空数组属性imageCount @0
- 步骤3：单个图片属性imageCount @1
- 步骤4：多个图片属性imageCount @3
- 步骤5：重复图片去重属性uniqueImageCount @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$mailTest = new mailTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($mailTest->setImagesTest(array('/tmp/test1.jpg', '/tmp/test2.png'))) && p('processed') && e('1'); // 步骤1：正常图片数组
r($mailTest->setImagesTest(array())) && p('imageCount') && e('0'); // 步骤2：空数组
r($mailTest->setImagesTest(array('/tmp/single.gif'))) && p('imageCount') && e('1'); // 步骤3：单个图片
r($mailTest->setImagesTest(array('/tmp/img1.jpg', '/tmp/img2.png', '/tmp/img3.bmp'))) && p('imageCount') && e('3'); // 步骤4：多个图片
r($mailTest->setImagesTest(array('/tmp/duplicate.jpg', '/tmp/duplicate.jpg', '/tmp/unique.png'))) && p('uniqueImageCount') && e('2'); // 步骤5：重复图片去重