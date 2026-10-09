#!/usr/bin/env php
<?php

/**

title=测试 fileModel::unlinkFileIfUnreferenced();
timeout=0
cid=0

- 步骤1：物理文件仍被其他文件记录引用时不删除物理文件 @exists
- 步骤2：删除副本附件记录后物理文件仍被原记录引用时不删除物理文件 @exists
- 步骤3：物理文件的所有引用记录都删除后删除物理文件 @unlinked
- 步骤4：没有任何文件记录引用物理文件时直接删除物理文件 @unlinked
- 步骤5：文件路径名为空时不删除任何物理文件 @exists

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/* 直接写入三条共用同一物理文件路径的记录，模拟复制对象时附件共用物理文件。 */
/* 注意：不能用 zendata 的 range('copy/dup.txt{3}')，以 .txt 结尾的 range 值会被当作文件列表引用。 */
global $tester;
$tester->dao->delete()->from(TABLE_FILE)->exec();

for($fileID = 1; $fileID <= 3; $fileID++)
{
    $fileRow = array(
        'id'         => $fileID,
        'pathname'   => 'copy/dup.txt',
        'title'      => 'dup file ' . $fileID,
        'extension'  => 'txt',
        'size'       => 100,
        'objectType' => 'bug',
        'objectID'   => 1,
        'gid'        => '',
        'addedBy'    => 'admin',
        'addedDate'  => '2023-01-01 10:00:00',
        'downloads'  => 0,
        'extra'      => '',
        'deleted'    => '0'
    );
    $tester->dao->insert(TABLE_FILE)->data($fileRow)->exec();
}

su('admin');

$fileTest = new fileModelTest();

/* 准备物理文件。Prepare physical files. */
$testDir = dirname(__FILE__) . '/temp/';
if(!is_dir($testDir)) mkdir($testDir, 0777, true);

$file1 = new stdclass();
$file1->pathname = 'copy/dup.txt';
$file1->realPath = $testDir . 'dup1.txt';
file_put_contents($file1->realPath, 'duplicate file 1');

$file2 = new stdclass();
$file2->pathname = 'copy/dup.txt';
$file2->realPath = $testDir . 'dup2.txt';
file_put_contents($file2->realPath, 'duplicate file 2');

$file3 = new stdclass();
$file3->pathname = 'copy/dup.txt';
$file3->realPath = $testDir . 'dup3.txt';
file_put_contents($file3->realPath, 'duplicate file 3');

$file4 = new stdclass();
$file4->pathname = 'copy/none.txt';
$file4->realPath = $testDir . 'none.txt';
file_put_contents($file4->realPath, 'no record file');

$file5 = new stdclass();
$file5->pathname = '';
$file5->realPath = $testDir . 'empty.txt';
file_put_contents($file5->realPath, 'empty pathname file');

/* 删除副本 bug 的附件记录，模拟删除复制出来的附件。Delete the file record of the copied bug. */
$tester->dao->delete()->from(TABLE_FILE)->where('id')->eq(2)->exec();
r($fileTest->unlinkFileIfUnreferencedTest($file1)) && p() && e('exists'); // 步骤1：物理文件仍被其他文件记录引用时不删除物理文件

/* 继续删除一条记录，模拟两个 bug 的附件记录共用同一物理文件。Delete one more record sharing the same physical file. */
$tester->dao->delete()->from(TABLE_FILE)->where('id')->eq(1)->exec();
r($fileTest->unlinkFileIfUnreferencedTest($file2)) && p() && e('exists'); // 步骤2：删除副本附件记录后物理文件仍被原记录引用时不删除物理文件

/* 删除最后一条引用记录，物理文件不再被引用。Delete the last record referencing the physical file. */
$tester->dao->delete()->from(TABLE_FILE)->where('id')->eq(3)->exec();
r($fileTest->unlinkFileIfUnreferencedTest($file3)) && p() && e('unlinked'); // 步骤3：物理文件的所有引用记录都删除后删除物理文件

r($fileTest->unlinkFileIfUnreferencedTest($file4)) && p() && e('unlinked'); // 步骤4：没有任何文件记录引用物理文件时直接删除物理文件

r($fileTest->unlinkFileIfUnreferencedTest($file5)) && p() && e('exists'); // 步骤5：文件路径名为空时不删除任何物理文件

/* 清理测试文件和目录。Clean up test files and directory. */
if(file_exists($file1->realPath)) unlink($file1->realPath);
if(file_exists($file2->realPath)) unlink($file2->realPath);
if(file_exists($file3->realPath)) unlink($file3->realPath);
if(file_exists($file4->realPath)) unlink($file4->realPath);
if(file_exists($file5->realPath)) unlink($file5->realPath);
if(is_dir($testDir)) rmdir($testDir);
