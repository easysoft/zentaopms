#!/usr/bin/env php
<?php

/**

title=测试 fileZen::unlinkRealFile();
timeout=0
cid=16543

- 执行fileTest模块的unlinkRealFileZenTest方法，参数是$multiPathFile 属性called @1
- 执行fileTest模块的unlinkRealFileZenTest方法，参数是$uniquePathFile 属性called @1
- 执行fileTest模块的unlinkRealFileZenTest方法，参数是$emptyFile 属性error @type_error
- 执行fileTest模块的unlinkRealFileZenTest方法，参数是$emptyPathFile 属性called @1
- 执行fileTest模块的unlinkRealFileZenTest方法，参数是$nullPathFile 属性called @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/filezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('file')->loadYaml('file_unlinkrealfile', false, 2)->gen(7);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$fileTest = new fileZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：存在多个相同路径的文件记录时不删除物理文件
$multiPathFile = new stdclass();
$multiPathFile->pathname = 'test/path/file1.txt';
r($fileTest->unlinkRealFileZenTest($multiPathFile)) && p('called') && e('1');

// 步骤2：唯一路径文件记录时删除物理文件
$uniquePathFile = new stdclass();
$uniquePathFile->pathname = 'test/path/unique.txt';
r($fileTest->unlinkRealFileZenTest($uniquePathFile)) && p('called') && e('1');

// 步骤3：文件对象为空时的处理
$emptyFile = null;
r($fileTest->unlinkRealFileZenTest($emptyFile)) && p('error') && e('type_error');

// 步骤4：文件对象路径名为空时的处理
$emptyPathFile = new stdclass();
$emptyPathFile->pathname = '';
r($fileTest->unlinkRealFileZenTest($emptyPathFile)) && p('called') && e('1');

// 步骤5：文件对象路径名为null时的处理
$nullPathFile = new stdclass();
$nullPathFile->pathname = null;
r($fileTest->unlinkRealFileZenTest($nullPathFile)) && p('called') && e('1');