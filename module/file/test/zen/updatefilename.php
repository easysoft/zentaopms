#!/usr/bin/env php
<?php

/**

title=测试 fileZen::updateFileName();
timeout=0
cid=16544

- 步骤1：正常更新txt文件名属性result @success
- 步骤2：更新文件名为空字符串属性result @fail
- 步骤3：更新文件名超过80个字符属性result @fail
- 步骤4：正常更新jpg文件名属性result @success
- 步骤5：正常更新zip文件名属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/filezen.unittest.class.php';

// 2. zendata数据准备
zenData('file')->loadYaml('file_updatefilename', false, 2)->gen(5);
zenData('action')->gen(0);
zenData('history')->gen(0);
zenData('user')->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$fileTest = new fileZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($fileTest->updateFileNameZenTest(1, 'newfile1', 'txt')) && p('result') && e('success'); // 步骤1：正常更新txt文件名
r($fileTest->updateFileNameZenTest(2, '', 'doc')) && p('result') && e('fail'); // 步骤2：更新文件名为空字符串
r($fileTest->updateFileNameZenTest(3, str_repeat('a', 81), 'pdf')) && p('result') && e('fail'); // 步骤3：更新文件名超过80个字符
r($fileTest->updateFileNameZenTest(4, 'newfile2', 'jpg')) && p('result') && e('success'); // 步骤4：正常更新jpg文件名
r($fileTest->updateFileNameZenTest(5, 'newfile3', 'zip')) && p('result') && e('success'); // 步骤5：正常更新zip文件名
