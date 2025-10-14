#!/usr/bin/env php
<?php

/**

title=测试 storyTao::doSaveUploadImage();
timeout=0
cid=0

- 步骤1：正常图片上传属性spec @原始内容<img src="{1.jpg}" alt="" />
- 步骤2：正常文档上传
 - 属性files @
- 步骤3：session无文件信息属性spec @原始内容
- 步骤4：文件不存在属性spec @原始内容
- 步骤5：空文件名属性spec @原始内容

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-5');
$story->product->range('1-3');
$story->title->range('测试需求{1-5}');
$story->type->range('story');
$story->status->range('draft');
$story->openedBy->range('admin');
$story->openedDate->range('`2024-01-01 10:00:00`');
$story->gen(5);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-5');
$storyspec->version->range('1');
$storyspec->title->range('测试需求{1-5}');
$storyspec->spec->range('这是需求描述{1-5}');
$storyspec->verify->range('这是验收标准{1-5}');
$storyspec->files->range('');
$storyspec->gen(5);

// 清理已有文件记录
$file = zenData('file');
$file->gen(0);

// 创建目录结构用于文件测试
if(!is_dir('/tmp/zentao_test')) mkdir('/tmp/zentao_test', 0777, true);
file_put_contents('/tmp/zentao_test/test_image.jpg', 'fake image content');
file_put_contents('/tmp/zentao_test/test_doc.pdf', 'fake pdf content');

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->doSaveUploadImageTest(1, 'test_image.jpg', 'image')) && p('spec') && e('原始内容<img src="{1.jpg}" alt="" />'); // 步骤1：正常图片上传
r($storyTest->doSaveUploadImageTest(2, 'test_doc.pdf', 'file')) && p('files') && e(',2'); // 步骤2：正常文档上传
r($storyTest->doSaveUploadImageTest(3, 'nonexist.jpg', 'empty_session')) && p('spec') && e('原始内容'); // 步骤3：session无文件信息
r($storyTest->doSaveUploadImageTest(4, 'missing_file.jpg', 'missing_file')) && p('spec') && e('原始内容'); // 步骤4：文件不存在
r($storyTest->doSaveUploadImageTest(5, '', 'empty_name')) && p('spec') && e('原始内容'); // 步骤5：空文件名