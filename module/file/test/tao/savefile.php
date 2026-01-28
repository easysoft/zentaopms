#!/usr/bin/env php
<?php

/**

title=测试 fileTao::saveFile();
timeout=0
cid=16540

- 执行fileTest模块的saveFileTest方法，参数是$normalFile  @6
- 执行fileTest模块的saveFileTest方法，参数是$skipFieldsFile, 'realpath, extra'  @7
- 执行fileTest模块的saveFileTest方法，参数是$emptyFile  @0
- 执行fileTest模块的saveFileTest方法，参数是$specialFile  @8
- 执行$savedFile
 - 属性title @Validate File.txt
 - 属性objectType @bug
 - 属性objectID @203
 - 属性extension @txt

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备
$table = zenData('file');
$table->id->range('1-5');
$table->pathname->range('file{5}');
$table->title->range('File{5}');
$table->extension->range('txt{5}');
$table->size->range('1024{5}');
$table->objectType->range('bug{5}');
$table->objectID->range('1{5}');
$table->addedBy->range('admin{5}');
$table->addedDate->range('`2024-01-01 10:00:00`,`2024-01-02 10:00:00`,`2024-01-03 10:00:00`,`2024-01-04 10:00:00`,`2024-01-05 10:00:00`');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$fileTest = new fileTaoTest();

// 5. 测试步骤1：正常保存文件数据
$normalFile = array(
    'pathname'   => '202401/02/normalfile.txt',
    'title'      => 'Normal File.txt',
    'extension'  => 'txt',
    'size'       => 2048,
    'objectType' => 'bug',
    'objectID'   => 200,
    'addedBy'    => 'admin',
    'addedDate'  => helper::now()
);
r($fileTest->saveFileTest($normalFile)) && p('') && e('6');

// 测试步骤2：保存文件时跳过指定字段
$skipFieldsFile = array(
    'pathname'   => '202401/02/skipfile.txt',
    'title'      => 'Skip Fields File.txt',
    'extension'  => 'txt',
    'size'       => 1024,
    'objectType' => 'task',
    'objectID'   => 201,
    'addedBy'    => 'admin',
    'addedDate'  => helper::now(),
    'extra'      => 'skip',
    'realpath'   => '/tmp/realpath2'
);
r($fileTest->saveFileTest($skipFieldsFile, 'realpath,extra')) && p('') && e('7');

// 测试步骤3：传入空数组参数
$emptyFile = array();
r($fileTest->saveFileTest($emptyFile)) && p('') && e('0');

// 测试步骤4：保存包含特殊字符的文件数据
$specialFile = array(
    'pathname'   => '202401/02/special_file.txt',
    'title'      => 'Special File.txt',
    'extension'  => 'txt',
    'size'       => 4096,
    'objectType' => 'story',
    'objectID'   => 202,
    'addedBy'    => 'admin',
    'addedDate'  => helper::now()
);
r($fileTest->saveFileTest($specialFile)) && p('') && e('8');

// 测试步骤5：保存文件数据验证数据库记录完整性
$validateFile = array(
    'pathname'   => '202401/02/validate.txt',
    'title'      => 'Validate File.txt',
    'extension'  => 'txt',
    'size'       => 8192,
    'objectType' => 'bug',
    'objectID'   => 203,
    'addedBy'    => 'admin',
    'addedDate'  => helper::now()
);
$insertID = $fileTest->saveFileTest($validateFile);
$savedFile = $fileTest->objectModel->getById($insertID);
r($savedFile) && p('title,objectType,objectID,extension') && e('Validate File.txt,bug,203,txt');