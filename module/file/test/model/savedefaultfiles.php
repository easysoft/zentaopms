#!/usr/bin/env php
<?php
/**

title=测试 fileModel->updateObjectID();
cid=16528

- 测试保存默认文件 1 到 需求 1001 extra 空 中 @6
- 测试保存默认文件 1 到 需求 1001 extra 1  中 @7
- 测试保存默认文件 1 到 需求 1001 1002 extra 空 中 @6,8,9

- 测试保存默认文件 1 到 需求 1001 1002 extra 1  中 @7,10,11

- 测试保存默认文件 1 到 任务 1001 extra 空 中 @12
- 测试保存默认文件 1 到 任务 1001 extra 1  中 @13
- 测试保存默认文件 1 到 任务 1001 1002 extra 空 中 @12,14,15

- 测试保存默认文件 1 到 任务 1001 1002 extra 1  中 @13,16,17

- 测试保存默认文件 1 2 到 需求 1001 extra 空 中 @6,8,18,19

- 测试保存默认文件 1 2 到 需求 1001 extra 1  中 @7,10,20,21

- 测试保存默认文件 1 2 到 需求 1001 1002 extra 空 中 @6,8,9,18,19,22,23,24,25

- 测试保存默认文件 1 2 到 需求 1001 1002 extra 1  中 @7,10,11,20,21,26,27,28,29

- 测试保存默认文件 1 2 到 任务 1001 extra 空 中 @12,14,30,31

- 测试保存默认文件 1 2 到 任务 1001 extra 1  中 @13,16,32,33

- 测试保存默认文件 1 2 到 任务 1001 1002 extra 空 中 @12,14,15,30,31,34,35,36,37

- 测试保存默认文件 1 2 到 任务 1001 1002 extra 1  中 @13,16,17,32,33,38,39,40,41

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
$zd_company = zenData('company');
$zd_company->id->range('1');
$zd_company->name->range('禅道软件');
$zd_company->admins->range('admin');
$zd_company->guest->range('0');
$zd_company->gen(1);
$dbh->exec("UPDATE zt_company SET admins = ',admin,' WHERE id = 1");
global $app;
$app->company = $dbh->query('SELECT * FROM zt_company WHERE id = 1')->fetch(PDO::FETCH_OBJ);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

zenData('file')->gen(5);
zenData('story')->gen(5);
zenData('task')->gen(5);

$uid = '98390890341';

$objectID   = array(1, array(1, 2, 3, 4, 5));
$objectType = array('story', 'task');
$extra      = array('', 1);
$fileIdList = array(array(1), array(1,2));

$file = new fileModelTest();

r($file->saveDefaultFilesTest($fileIdList[0], $objectType[0], $objectID[0], $extra[0])) && p() && e('0');                          // 测试保存默认文件 1 到 需求 1 extra 空 中
r($file->saveDefaultFilesTest($fileIdList[0], $objectType[0], $objectID[0], $extra[1])) && p() && e('0');                          // 测试保存默认文件 1 到 需求 1 extra 1  中
r($file->saveDefaultFilesTest($fileIdList[0], $objectType[0], $objectID[1], $extra[0])) && p() && e('0');                          // 测试保存默认文件 1 到 需求 1 2 3 4 5 extra 空 中
r($file->saveDefaultFilesTest($fileIdList[0], $objectType[0], $objectID[1], $extra[1])) && p() && e('0');           // 测试保存默认文件 1 到 需求 1 2 3 4 5 extra 1  中
r($file->saveDefaultFilesTest($fileIdList[0], $objectType[1], $objectID[0], $extra[0])) && p() && e('0');                          // 测试保存默认文件 1 到 任务 1 extra 空 中
r($file->saveDefaultFilesTest($fileIdList[0], $objectType[1], $objectID[0], $extra[1])) && p() && e('0');                         // 测试保存默认文件 1 到 任务 1 extra 1  中
r($file->saveDefaultFilesTest($fileIdList[0], $objectType[1], $objectID[1], $extra[0])) && p() && e('0');                          // 测试保存默认文件 1 到 任务 1 2 3 4 5 extra 空 中
r($file->saveDefaultFilesTest($fileIdList[0], $objectType[1], $objectID[1], $extra[1])) && p() && e('0');          // 测试保存默认文件 1 到 任务 1 2 3 4 5 extra 1  中
r($file->saveDefaultFilesTest($fileIdList[1], $objectType[0], $objectID[0], $extra[0])) && p() && e('0');                          // 测试保存默认文件 1 2 到 需求 1 extra 空 中
r($file->saveDefaultFilesTest($fileIdList[1], $objectType[0], $objectID[0], $extra[1])) && p() && e('0');                 // 测试保存默认文件 1 2 到 需求 1 extra 1  中
r($file->saveDefaultFilesTest($fileIdList[1], $objectType[0], $objectID[1], $extra[0])) && p() && e('0');                          // 测试保存默认文件 1 2 到 需求 1 2 3 4 5 extra 空 中
r($file->saveDefaultFilesTest($fileIdList[1], $objectType[1], $objectID[0], $extra[0])) && p() && e('0');                          // 测试保存默认文件 1 2 到 任务 1 extra 空 中
r($file->saveDefaultFilesTest($fileIdList[1], $objectType[1], $objectID[0], $extra[1])) && p() && e('0');                // 测试保存默认文件 1 2 到 任务 1 extra 1  中
r($file->saveDefaultFilesTest($fileIdList[1], $objectType[1], $objectID[1], $extra[0])) && p() && e('0');                          // 测试保存默认文件 1 2 到 任务 1 2 3 4 5 extra 空 中
