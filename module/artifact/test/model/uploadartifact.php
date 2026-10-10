#!/usr/bin/env php
<?php

/**

title=测试 artifactModel::uploadArtifact();
timeout=0
cid=0

- 步骤1：制品库不存在时返回false @0
- 步骤2：制品库ID为0时返回false @0
- 步骤3：制品库ID为负数时返回false @0
- 步骤4：制品库ID超出范围时返回false @0
- 步骤5：正常上传返回上传记录数 @1
- 步骤6：上传文件的属性为normal @normal
- 步骤7：上传文件的格式为file @file
- 步骤8：上传文件的路径与文件名一致 @ut-upload-artifact-4.txt

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$user = zenData('user');
$user->id->range('1');
$user->account->range('admin');
$user->realname->range('admin');
$user->password->range('e10adc3949ba59abbe56e057f20f883e');
$user->visions->range('rnd');
$user->deleted->range('0');
$user->gen(1);

$entry = zenData('entry');
$entry->id->range('1');
$entry->name->range('GitFox');
$entry->account->range('admin');
$entry->code->range('gitfox');
$entry->key->range('gitfox');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->createdBy->range('admin');
$entry->createdDate->range('[2026-01-01 00:00:00]');
$entry->calledTime->range('0');
$entry->editedBy->range('admin');
$entry->editedDate->range('[2026-01-01 00:00:00]');
$entry->deleted->range('0');
$entry->gen(1);

zenData('ops_artifact_libs')->gen(0);
$lib = zenData('ops_artifact_libs');
$lib->id->range('1');
$lib->spaceID->range('1');
$lib->repoID->range('0');
$lib->name->range('ut-artifact-lib');
$lib->code->range('utartifactlib');
$lib->desc->range('制品库上传用例数据');
$lib->type->range('file');
$lib->scope->range('space');
$lib->createdBy->range('admin');
$lib->createdDate->range('[2026-01-01 00:00:00]');
$lib->editedBy->range('admin');
$lib->editedDate->range('[2026-01-01 00:00:00]');
$lib->deleted->range('0');
$lib->gen(1);

su('admin');

/* 每次执行使用唯一的目录，避免GitFox中同名同内容的文件重复上传被跳过。 */
$group = 'ut-upload-' . date('YmdHis') . mt_rand(1000, 9999);

$fileName1 = 'ut-upload-artifact-1.txt';
$fileName2 = 'ut-upload-artifact-2.txt';
$fileName3 = 'ut-upload-artifact-3.txt';
$fileName4 = 'ut-upload-artifact-4.txt';
foreach(array($fileName1, $fileName2, $fileName3, $fileName4) as $fileName)
{
    file_put_contents('/tmp/' . $fileName, 'artifact upload unit test ' . $fileName);
}

$file1 = array('name' => $fileName1, 'tmp_name' => '/tmp/' . $fileName1);
$file2 = array('name' => $fileName2, 'tmp_name' => '/tmp/' . $fileName2);
$file3 = array('name' => $fileName3, 'tmp_name' => '/tmp/' . $fileName3);
$file4 = array('name' => $fileName4, 'tmp_name' => '/tmp/' . $fileName4);

$testObj = new artifactModelTest();
r($testObj->uploadArtifactTest(2, $file1, $group))                                    && p() && e('0');                   // 步骤1：制品库不存在时返回false
r($testObj->uploadArtifactTest(0, $file1, $group))                                    && p() && e('0');                   // 步骤2：制品库ID为0时返回false
r($testObj->uploadArtifactTest(-1, $file1, $group))                                   && p() && e('0');                   // 步骤3：制品库ID为负数时返回false
r($testObj->uploadArtifactTest(999999, $file1, $group))                               && p() && e('0');                   // 步骤4：制品库ID超出范围时返回false
r(count($testObj->uploadArtifactTest(1, $file1, $group)))                             && p() && e('1');                   // 步骤5：正常上传返回上传记录数
r($testObj->uploadArtifactFieldTest(1, $file2, $group, '', 'Attr'))                   && p() && e('normal');              // 步骤6：上传文件的属性为normal
r($testObj->uploadArtifactFieldTest(1, $file3, $group, '', 'format'))                 && p() && e('file');                // 步骤7：上传文件的格式为file
r($testObj->uploadArtifactFieldTest(1, $file4, $group, '', 'path'))                   && p() && e('ut-upload-artifact-4.txt'); // 步骤8：上传文件的路径与文件名一致
