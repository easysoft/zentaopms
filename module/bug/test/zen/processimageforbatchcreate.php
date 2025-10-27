#!/usr/bin/env php
<?php

/**

title=测试 bugZen::processImageForBatchCreate();
timeout=0
cid=0

- 期望返回包含png扩展名的文件信息属性extension @png
- 期望返回空数组 @0
- 期望返回空数组 @0
- 期望返回文件信息但不是图片类型属性extension @txt
- 期望返回空数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$bug = zenData('bug');
$bug->id->range('1-10');
$bug->title->range('测试Bug标题{1-10}');
$bug->steps->range('测试步骤{1-10}');
$bug->product->range('1');
$bug->execution->range('101');
$bug->status->range('active');
$bug->gen(5);

$file = zenData('file');
$file->id->range('1-10');
$file->pathname->range('test{1-10}.png');
$file->title->range('测试文件{1-10}');
$file->extension->range('png,jpg,gif,txt,doc');
$file->size->range('1024-10240:1024');
$file->objectType->range('bug');
$file->objectID->range('1-5');
$file->addedBy->range('admin');
$file->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建测试用的bug对象
$testBug = new stdclass();
$testBug->id = 1;
$testBug->title = '测试Bug';
$testBug->steps = '原始步骤';

// 创建测试用的图片文件数据
$validImageFile = array(
    'realpath' => '/tmp/test_image.png',
    'pathname' => 'test_image.png',
    'extension' => 'png',
    'title' => '测试图片',
    'size' => 2048
);

$nonImageFile = array(
    'realpath' => '/tmp/test_file.txt',
    'pathname' => 'test_file.txt',
    'extension' => 'txt',
    'title' => '测试文本文件',
    'size' => 1024
);

$bugImagesFiles = array(
    'valid_image' => $validImageFile,
    'non_image' => $nonImageFile
);

// 步骤1：正常情况 - 有效的图片文件上传
r($bugTest->processImageForBatchCreateTest($testBug, 'valid_image', $bugImagesFiles)) && p('extension') && e('png'); // 期望返回包含png扩展名的文件信息

// 步骤2：边界值 - 空的uploadImage参数
r($bugTest->processImageForBatchCreateTest($testBug, null, $bugImagesFiles)) && p() && e('0'); // 期望返回空数组

// 步骤3：异常输入 - 不存在的图片文件key
r($bugTest->processImageForBatchCreateTest($testBug, 'non_exist_image', $bugImagesFiles)) && p() && e('0'); // 期望返回空数组

// 步骤4：业务规则 - 处理非图片文件类型
r($bugTest->processImageForBatchCreateTest($testBug, 'non_image', $bugImagesFiles)) && p('extension') && e('txt'); // 期望返回文件信息但不是图片类型

// 步骤5：错误处理 - 空的uploadImage字符串
r($bugTest->processImageForBatchCreateTest($testBug, '', $bugImagesFiles)) && p() && e('0'); // 期望返回空数组