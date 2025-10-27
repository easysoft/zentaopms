#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getThumbnail();
timeout=0
cid=0

- 步骤1：正常情况多个screen有cover第0条的cover属性 @file-read-2.png
- 步骤2：空数组输入 @0
- 步骤3：无关联图片的screen第0条的cover属性 @~~
- 步骤4：混合情况返回数组 @7
- 步骤5：单个screen有多个图片文件第0条的cover属性 @file-read-10.png

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 构建测试数据
$screensWithImages = array();
for($i = 1; $i <= 3; $i++) {
    $screen = new stdclass();
    $screen->id = $i;
    $screen->name = '测试大屏' . $i;
    $screensWithImages[] = $screen;
}

$screensWithoutImages = array();
for($i = 6; $i <= 7; $i++) {
    $screen = new stdclass();
    $screen->id = $i;
    $screen->name = '测试大屏' . $i;
    $screensWithoutImages[] = $screen;
}

$mixedScreens = array();
for($i = 1; $i <= 7; $i++) {
    $screen = new stdclass();
    $screen->id = $i;
    $screen->name = '测试大屏' . $i;
    $mixedScreens[] = $screen;
}

$singleScreenWithMultipleImages = array();
$screen = new stdclass();
$screen->id = 9;
$screen->name = '测试大屏9';
$singleScreenWithMultipleImages[] = $screen;

r($screenTest->getThumbnailTest($screensWithImages)) && p('0:cover') && e('file-read-2.png'); // 步骤1：正常情况多个screen有cover
r($screenTest->getThumbnailTest(array())) && p() && e('0'); // 步骤2：空数组输入
r($screenTest->getThumbnailTest($screensWithoutImages)) && p('0:cover') && e('~~'); // 步骤3：无关联图片的screen
r($screenTest->getThumbnailTest($mixedScreens)) && p() && e('7'); // 步骤4：混合情况返回数组
r($screenTest->getThumbnailTest($singleScreenWithMultipleImages)) && p('0:cover') && e('file-read-10.png'); // 步骤5：单个screen有多个图片文件