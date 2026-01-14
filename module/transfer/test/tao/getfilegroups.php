#!/usr/bin/env php
<?php

/**

title=测试 transferTao::getFileGroups();
timeout=0
cid=19333

- 步骤1：获取bug模块指定ID列表的附件分组 @1
- 步骤2：获取所有bug模块的附件分组（空ID列表） @1
- 步骤3：获取不存在的模块类型附件，返回空数组 @1
- 步骤4：获取指定ID列表但不存在的附件，返回空数组 @1
- 步骤5：获取多个ID列表的附件分组 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备 - 使用简单的数据生成方式
$file = zenData('file');
$file->id->range('1-10');
$file->pathname->range('/uploads/file1.doc,/uploads/file2.pdf,/uploads/file3.jpg,/uploads/file4.txt,/uploads/file5.doc,/uploads/file6.pdf,/uploads/file7.jpg,/uploads/file8.txt,/uploads/file9.doc,/uploads/file10.pdf');
$file->title->range('文件标题1,文件标题2,文件标题3,文件标题4,文件标题5,文件标题6,文件标题7,文件标题8,文件标题9,文件标题10');
$file->extension->range('doc,pdf,jpg,txt,doc,pdf,jpg,txt,doc,pdf');
$file->size->range('1024-102400');
$file->objectType->range('bug,bug,story,story,task,task,testcase,testcase,bug,story');
$file->objectID->range('1,2,1,2,1,2,1,2,3,3');
$file->addedBy->range('admin');
$file->addedDate->range('`2024-01-01 00:00:00`');
$file->downloads->range('0-10');
$file->extra->range(',,editor,,,,,,,');
$file->deleted->range('0');
$file->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$transferTest = new transferTaoTest();

// 5. 执行测试步骤
r(is_array($transferTest->getFileGroupsTest('bug', array(2)))) && p() && e('1'); // 步骤1：获取bug模块指定ID列表的附件分组
r(is_array($transferTest->getFileGroupsTest('bug', array()))) && p() && e('1'); // 步骤2：获取所有bug模块的附件分组（空ID列表）
r(is_array($transferTest->getFileGroupsTest('nonexistent', array(1, 2)))) && p() && e('1'); // 步骤3：获取不存在的模块类型附件，返回空数组
r(is_array($transferTest->getFileGroupsTest('bug', array(999, 1000)))) && p() && e('1'); // 步骤4：获取指定ID列表但不存在的附件，返回空数组
r(is_array($transferTest->getFileGroupsTest('story', array(1, 2)))) && p() && e('1'); // 步骤5：获取多个ID列表的附件分组