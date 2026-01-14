#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getThumbnail();
timeout=0
cid=18258

- 步骤1：测试单个screen有一张图片的情况第0条的cover属性 @file-read-2.png
- 步骤2：测试单个screen有多张图片的情况第0条的cover属性 @file-read-10.png
- 步骤3：测试多个screens的情况
 - 第0条的cover属性 @file-read-2.png
 - 第1条的cover属性 @file-read-4.png
 - 第2条的cover属性 @file-read-6.png
- 步骤4：测试screen没有图片的情况第0条的cover属性 @~~
- 步骤5：测试空数组输入的情况 @0
- 步骤6：测试混合情况
 - 第0条的cover属性 @file-read-2.png
 - 第1条的cover属性 @~~
 - 第2条的cover属性 @file-read-4.png

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$screenTest = new screenModelTest();

// 4. 测试步骤

// 步骤1：测试单个screen有一张图片的情况
$screen1 = array((object)array('id' => 1, 'name' => 'Screen 1'));
r($screenTest->getThumbnailTest($screen1)) && p('0:cover') && e('file-read-2.png'); // 步骤1：测试单个screen有一张图片的情况

// 步骤2：测试单个screen有多张图片的情况(screen id=9有3张图片,应该选最后一张id=10)
$screen2 = array((object)array('id' => 9, 'name' => 'Screen 9'));
r($screenTest->getThumbnailTest($screen2)) && p('0:cover') && e('file-read-10.png'); // 步骤2：测试单个screen有多张图片的情况

// 步骤3：测试多个screens的情况
$screens3 = array(
    (object)array('id' => 1, 'name' => 'Screen 1'),
    (object)array('id' => 2, 'name' => 'Screen 2'),
    (object)array('id' => 3, 'name' => 'Screen 3')
);
r($screenTest->getThumbnailTest($screens3)) && p('0:cover;1:cover;2:cover') && e('file-read-2.png;file-read-4.png;file-read-6.png'); // 步骤3：测试多个screens的情况

// 步骤4：测试screen没有图片的情况(screen id=99没有关联图片)
$screen4 = array((object)array('id' => 99, 'name' => 'Screen 99'));
r($screenTest->getThumbnailTest($screen4)) && p('0:cover') && e('~~'); // 步骤4：测试screen没有图片的情况

// 步骤5：测试空数组输入的情况
r($screenTest->getThumbnailTest(array())) && p() && e('0'); // 步骤5：测试空数组输入的情况

// 步骤6：测试混合情况(部分有图片,部分无图片)
$screens6 = array(
    (object)array('id' => 1, 'name' => 'Screen 1'),
    (object)array('id' => 88, 'name' => 'Screen 88'),
    (object)array('id' => 2, 'name' => 'Screen 2')
);
r($screenTest->getThumbnailTest($screens6)) && p('0:cover;1:cover;2:cover') && e('file-read-2.png;~~;file-read-4.png'); // 步骤6：测试混合情况