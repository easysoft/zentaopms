#!/usr/bin/env php
<?php

/**

title=测试 storyTao::doSaveUploadImage();
timeout=0
cid=18620

- 执行doSaveUploadImageTest(1, 'test_image.jpg', 'image')模块的spec) > 10方法  @1
- 执行doSaveUploadImageTest(2, 'test_doc.pdf', 'file')模块的files) > 0方法  @1
- 执行storyTest模块的doSaveUploadImageTest方法，参数是3, 'test.jpg', 'empty_session' 属性spec @原始内容
- 执行storyTest模块的doSaveUploadImageTest方法，参数是4, 'nonexistent.jpg', 'empty_name' 属性spec @原始内容
- 执行storyTest模块的doSaveUploadImageTest方法，参数是5, 'missing.jpg', 'missing_file' 属性spec @原始内容

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

// 创建测试目录和文件
$testDir = '/tmp/zentao_test';
if(!is_dir($testDir)) mkdir($testDir, 0777, true);
$imageFile = $testDir . '/test_image.jpg';
$docFile = $testDir . '/test_doc.pdf';
file_put_contents($imageFile, 'test image content');
file_put_contents($docFile, 'test document content');

$storyTest = new storyTaoTest();

r(strlen($storyTest->doSaveUploadImageTest(1, 'test_image.jpg', 'image')->spec) > 10) && p() && e('1');
r(strlen($storyTest->doSaveUploadImageTest(2, 'test_doc.pdf', 'file')->files) > 0) && p() && e('1');
r($storyTest->doSaveUploadImageTest(3, 'test.jpg', 'empty_session')) && p('spec') && e('原始内容');
r($storyTest->doSaveUploadImageTest(4, 'nonexistent.jpg', 'empty_name')) && p('spec') && e('原始内容');
r($storyTest->doSaveUploadImageTest(5, 'missing.jpg', 'missing_file')) && p('spec') && e('原始内容');