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
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// Note: Testing without extensive data generation to avoid database issues

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$mailTest = new mailTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($mailTest->getImagesByFileIDTest(array('1' => array('/file-read-1.jpg', '/file-read-2.png'), '2' => array('1', '2'))))) && p() && e(2); // 步骤1：正常情况-有效图片文件ID
r(count($mailTest->getImagesByFileIDTest(array()))) && p() && e(0); // 步骤2：空数组
r(count($mailTest->getImagesByFileIDTest(array('1' => array('/file-read-999.jpg'), '2' => array('999'))))) && p() && e(0); // 步骤3：无效文件ID
r(count($mailTest->getImagesByFileIDTest(array('1' => array('/file-read-9.pdf'), '2' => array('9'))))) && p() && e(0); // 步骤4：非图片文件
r(count($mailTest->getImagesByFileIDTest(array('1' => array('/file-read-1.jpg', '/file-read-999.jpg', '/file-read-2.png'), '2' => array('1', '999', '2'))))) && p() && e(2); // 步骤5：混合有效无效ID