#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getThumbnail();
timeout=0
cid=0

- 步骤1：正常情况多个screen有cover第0条的cover属性 @/home/z/rzto/module/screen/test/model/getthumbnail.php?m=file&f=read&t=png&fileID=2
- 步骤2：空数组输入 @0
- 步骤3：screen有关联图片文件第0条的cover属性 @/home/z/rzto/module/screen/test/model/getthumbnail.php?m=file&f=read&t=png&fileID=8
- 步骤4：混合情况返回数组 @Array
- 步骤5：单个screen有cover第0条的cover属性 @/home/z/rzto/module/screen/test/model/getthumbnail.php?m=file&f=read&t=png&fileID=2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. zendata数据准备
$screen = zenData('screen');
$screen->id->range('1-5');
$screen->name->range('测试大屏1,测试大屏2,测试大屏3,测试大屏4,测试大屏5');
$screen->dimension->range('1-3');
$screen->gen(5);

$file = zenData('file');
$file->id->range('1-10');
$file->objectType->range('screen{10}');
$file->objectID->range('1,1,2,2,3,4,5,6,7,8');
$file->title->range('thumbnail1.png,thumbnail2.png,thumbnail3.png,thumbnail4.png,thumbnail5.png,thumbnail6.png,thumbnail7.png,thumbnail8.png,thumbnail9.png,thumbnail10.png');
$file->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
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
for($i = 1; $i <= 5; $i++) {
    $screen = new stdclass();
    $screen->id = $i;
    $screen->name = '测试大屏' . $i;
    $mixedScreens[] = $screen;
}

$singleScreenWithImage = array();
$screen = new stdclass();
$screen->id = 1;
$screen->name = '测试大屏1';
$singleScreenWithImage[] = $screen;

r($screenTest->getThumbnailTest($screensWithImages)) && p('0:cover') && e('/home/z/rzto/module/screen/test/model/getthumbnail.php?m=file&f=read&t=png&fileID=2'); // 步骤1：正常情况多个screen有cover
r($screenTest->getThumbnailTest(array())) && p() && e('0'); // 步骤2：空数组输入
r($screenTest->getThumbnailTest($screensWithoutImages)) && p('0:cover') && e('/home/z/rzto/module/screen/test/model/getthumbnail.php?m=file&f=read&t=png&fileID=8'); // 步骤3：screen有关联图片文件
r($screenTest->getThumbnailTest($mixedScreens)) && p() && e('Array'); // 步骤4：混合情况返回数组
r($screenTest->getThumbnailTest($singleScreenWithImage)) && p('0:cover') && e('/home/z/rzto/module/screen/test/model/getthumbnail.php?m=file&f=read&t=png&fileID=2'); // 步骤5：单个screen有cover
