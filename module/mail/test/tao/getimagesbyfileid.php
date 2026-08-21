#!/usr/bin/env php
<?php

/**

title=测试 mailTao::getImagesByFileID();
timeout=0
cid=17031

- 步骤1：正常情况-有效图片文件ID @2
- 步骤2：空数组 @0
- 步骤3：无效文件ID @0
- 步骤4：非图片文件 @0
- 步骤5：混合有效无效ID @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$file = zenData('file');
$file->id->range('1-3,9');
$file->pathname->range('202301/test1.jpg,202301/test2.png,202301/test3.gif,202301/test9.pdf');
$file->title->range('图片1,图片2,图片3,文档9');
$file->extension->range('jpg,png,gif,pdf');
$file->size->range('1024,2048,3072,4096');
$file->objectType->range('mail');
$file->objectID->range('1-4');
$file->addedBy->range('admin');
$file->addedDate->range('`2023-01-01 10:00:00`');
$file->gen(4);

/* 生成与图片记录对应的真实物理文件。Create real files matching the image records. */
$uploadRoot = $tester->app->getAppRoot() . 'www/data/upload/1/';
foreach(array('202301/test1.jpg', '202301/test2.png', '202301/test3.gif') as $filePath)
{
    $realPath = $uploadRoot . $filePath;
    if(!is_dir(dirname($realPath))) mkdir(dirname($realPath), 0777, true);
    file_put_contents($realPath, 'unittest image');
}

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$mailTest = new mailTaoTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($mailTest->getImagesByFileIDTest(array('1' => array('/file-read-1.jpg', '/file-read-2.png'), '2' => array('1', '2'))))) && p() && e(2); // 步骤1：正常情况-有效图片文件ID
r(count($mailTest->getImagesByFileIDTest(array()))) && p() && e(0); // 步骤2：空数组
r(count($mailTest->getImagesByFileIDTest(array('1' => array('/file-read-999.jpg'), '2' => array('999'))))) && p() && e(0); // 步骤3：无效文件ID
r(count($mailTest->getImagesByFileIDTest(array('1' => array('/file-read-9.pdf'), '2' => array('9'))))) && p() && e(0); // 步骤4：非图片文件
r(count($mailTest->getImagesByFileIDTest(array('1' => array('/file-read-1.jpg', '/file-read-999.jpg', '/file-read-2.png'), '2' => array('1', '999', '2'))))) && p() && e(2); // 步骤5：混合有效无效ID
