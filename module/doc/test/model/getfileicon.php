#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

/**

title=测试 docModel->getFileIcon();
cid=1

- 获取txt类型文件的图标 @<i class='file-icon icon icon-file-text'></i>
- 获取doc类型文件的图标 @<i class='file-icon icon icon-file-word'></i>
- 获取wps类型文件的图标 @<i class='file-icon icon icon-file'></i>
- 获取pdf类型文件的图标 @<i class='file-icon icon icon-file-pdf'></i>
- 获取ppt类型文件的图标 @<i class='file-icon icon icon-file-powerpoint'></i>
- 获取xls类型文件的图标 @<i class='file-icon icon icon-file-excel'></i>
- 获取avi类型文件的图标 @<i class='file-icon icon icon-file-video'></i>
- 获取mp3类型文件的图标 @<i class='file-icon icon icon-file-audio'></i>
- 获取rar类型文件的图标 @<i class='file-icon icon icon-file-archive'></i>

*/

zdTable('file')->gen(45);
zdTable('user')->gen(5);
su('admin');

$docTester = new docTest();
$files     = $docTester->getFileIconTest();

r($files[1])  && p() && e("<i class='file-icon icon icon-file-text'></i>");       // 获取txt类型文件的图标
r($files[2])  && p() && e("<i class='file-icon icon icon-file-word'></i>");       // 获取doc类型文件的图标
r($files[4])  && p() && e("<i class='file-icon icon icon-file'></i>");            // 获取wps类型文件的图标
r($files[7])  && p() && e("<i class='file-icon icon icon-file-pdf'></i>");        // 获取pdf类型文件的图标
r($files[8])  && p() && e("<i class='file-icon icon icon-file-powerpoint'></i>"); // 获取ppt类型文件的图标
r($files[10]) && p() && e("<i class='file-icon icon icon-file-excel'></i>");      // 获取xls类型文件的图标
r($files[24]) && p() && e("<i class='file-icon icon icon-file-video'></i>");      // 获取avi类型文件的图标
r($files[27]) && p() && e("<i class='file-icon icon icon-file-audio'></i>");      // 获取mp3类型文件的图标
r($files[33]) && p() && e("<i class='file-icon icon icon-file-archive'></i>");    // 获取rar类型文件的图标
