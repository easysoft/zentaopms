#!/usr/bin/env php
<?php

/**

title=测试 fileModel::getByGid();
timeout=0
cid=0

- 步骤1：正常gid查询
 - 属性gid @test_gid_001
 - 属性title @file1
- 步骤2：通过空gid的title查询
 - 属性gid @
 - 属性title @gid_test_1
- 步骤3：不存在的gid查询 @alse
- 步骤4：特殊字符gid
 - 属性gid @special_gid_123
 - 属性title @file3
- 步骤5：正常gid边界测试
 - 属性gid @normal_gid_abc
 - 属性title @file4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

$file = zenData('file');
$file->gid->range('test_gid_001,test_gid_002,{1},special_gid_123,normal_gid_abc,{5}');
$file->title->range('file1,file2,gid_test_1,file3,file4,{5}');
$file->pathname->range('FILE_NOT_FOUND{10}');
$file->gen(10);

su('admin');

global $tester;
$tester->loadModel('file');

r($tester->file->getByGid('test_gid_001')) && p('gid,title') && e('test_gid_001,file1'); // 步骤1：正常gid查询
r($tester->file->getByGid('gid_test_1')) && p('gid,title') && e(',gid_test_1'); // 步骤2：通过空gid的title查询  
r($tester->file->getByGid('nonexistent_gid')) && p() && e(false); // 步骤3：不存在的gid查询
r($tester->file->getByGid('special_gid_123')) && p('gid,title') && e('special_gid_123,file3'); // 步骤4：特殊字符gid
r($tester->file->getByGid('normal_gid_abc')) && p('gid,title') && e('normal_gid_abc,file4'); // 步骤5：正常gid边界测试