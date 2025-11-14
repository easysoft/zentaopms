#!/usr/bin/env php
<?php

/**

title=测试 fileModel::deleteByObject();
timeout=0
cid=16497

- 步骤1：删除story类型且objectID为1的文件 @1
- 步骤2：删除不存在的objectType和objectID组合 @1
- 步骤3：删除task类型且objectID为2的文件 @1
- 步骤4：删除已删除记录（软删除验证） @1
- 步骤5：删除bug类型且objectID为3的文件 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

$file = zenData('file');
$file->objectType->range('story{3}, task{3}, bug{2}, project{2}');
$file->objectID->range('1-5');
$file->deleted->range('0');
$file->gen(10);

su('admin');

global $tester;
$tester->loadModel('file');

r($tester->file->deleteByObject('story', 1)) && p() && e(1); // 步骤1：删除story类型且objectID为1的文件
r($tester->file->deleteByObject('nonexist', 999)) && p() && e(1); // 步骤2：删除不存在的objectType和objectID组合
r($tester->file->deleteByObject('task', 2)) && p() && e(1); // 步骤3：删除task类型且objectID为2的文件
r($tester->file->deleteByObject('story', 1)) && p() && e(1); // 步骤4：删除已删除记录（软删除验证）
r($tester->file->deleteByObject('bug', 3)) && p() && e(1); // 步骤5：删除bug类型且objectID为3的文件