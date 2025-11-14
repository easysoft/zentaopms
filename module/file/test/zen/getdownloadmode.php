#!/usr/bin/env php
<?php

/**

title=测试 fileZen::getDownloadMode();
timeout=0
cid=16542

- 步骤1：图片文件左键点击 @open
- 步骤2：文本文件左键点击 @open
- 步骤3：视频文件左键点击 @open
- 步骤4：图片文件右键点击 @down
- 步骤5：不支持文件类型左键点击 @down

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/filezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$fileTest = new fileZenTest();

// 4. 创建测试文件对象
$jpgFile = new stdclass();
$jpgFile->extension = 'jpg';

$txtFile = new stdclass();
$txtFile->extension = 'txt';

$mp4File = new stdclass();
$mp4File->extension = 'mp4';

$docFile = new stdclass();
$docFile->extension = 'doc';

$pngFile = new stdclass();
$pngFile->extension = 'png';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($fileTest->getDownloadModeZenTest($jpgFile, 'left')) && p() && e('open');  // 步骤1：图片文件左键点击
r($fileTest->getDownloadModeZenTest($txtFile, 'left')) && p() && e('open');  // 步骤2：文本文件左键点击
r($fileTest->getDownloadModeZenTest($mp4File, 'left')) && p() && e('open');  // 步骤3：视频文件左键点击
r($fileTest->getDownloadModeZenTest($jpgFile, 'right')) && p() && e('down'); // 步骤4：图片文件右键点击
r($fileTest->getDownloadModeZenTest($docFile, 'left')) && p() && e('down');  // 步骤5：不支持文件类型左键点击