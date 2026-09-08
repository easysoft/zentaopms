#!/usr/bin/env php
<?php

/**

title=测试 docModel->getLibFiles() 文档附件随文档状态和版本同步显示;
timeout=0
cid=16210

- 测试从文档当前版本移除的附件不在附件库中附件数 @4
- 测试从文档当前版本移除的附件不在附件库中显示 @102,103,105,106
- 测试恢复文档后附件在附件库中附件数 @5
- 测试恢复文档后附件在附件库中恢复显示 @102,103,104,105,106
- 测试再次从文档当前版本移除附件后附件库中附件数 @4
- 测试再次从文档当前版本移除附件后附件库同步隐藏 @102,103,104,106

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester, $config;

zenData('user')->gen(5);
su('admin');

/* 清空相关数据表，使用固定数据构造测试场景。 */
zenData('doclib')->gen(0);
zenData('doc')->gen(0);
zenData('doccontent')->gen(0);
zenData('file')->gen(0);

$now    = helper::now();
$vision = $config->vision;

$tester->dao->insert(TABLE_DOCLIB)->set('id')->eq(1)->set('name')->eq('产品文档库')->set('type')->eq('product')->set('product')->eq(1)->set('acl')->eq('open')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();

/* 文档1：附件101已从当前版本移除，附件102和103仍在当前版本。 */
$tester->dao->insert(TABLE_DOC)->set('id')->eq(1)->set('product')->eq(1)->set('lib')->eq(1)->set('title')->eq('文档1')->set('type')->eq('text')->set('status')->eq('normal')->set('version')->eq(2)->set('vision')->eq($vision)->set('deleted')->eq(0)->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(1)->set('version')->eq(1)->set('files')->eq('101')->set('title')->eq('文档1')->set('content')->eq('')->set('type')->eq('text')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(1)->set('version')->eq(2)->set('files')->eq('102,103')->set('title')->eq('文档1')->set('content')->eq('')->set('type')->eq('text')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();

/* 文档2：已删除文档，附件104。 */
$tester->dao->insert(TABLE_DOC)->set('id')->eq(2)->set('product')->eq(1)->set('lib')->eq(1)->set('title')->eq('文档2')->set('type')->eq('text')->set('status')->eq('normal')->set('version')->eq(1)->set('vision')->eq($vision)->set('deleted')->eq(1)->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(2)->set('version')->eq(1)->set('files')->eq('104')->set('title')->eq('文档2')->set('content')->eq('')->set('type')->eq('text')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();

/* 文档3：正常文档，附件105和106。 */
$tester->dao->insert(TABLE_DOC)->set('id')->eq(3)->set('product')->eq(1)->set('lib')->eq(1)->set('title')->eq('文档3')->set('type')->eq('text')->set('status')->eq('normal')->set('version')->eq(1)->set('vision')->eq($vision)->set('deleted')->eq(0)->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(3)->set('version')->eq(1)->set('files')->eq('105,106')->set('title')->eq('文档3')->set('content')->eq('')->set('type')->eq('text')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();

$docFileList = array(101 => 1, 102 => 1, 103 => 1, 104 => 2, 105 => 3, 106 => 3);
foreach($docFileList as $fileID => $docID)
{
    $tester->dao->insert(TABLE_FILE)->set('id')->eq($fileID)->set('pathname')->eq("2026/test{$fileID}.txt")->set('title')->eq("附件{$fileID}")->set('extension')->eq('txt')->set('size')->eq(100)->set('objectType')->eq('doc')->set('objectID')->eq($docID)->set('deleted')->eq(0)->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
}

$docTester = new docModelTest();
$getFileKeyList = function() use ($docTester)
{
    $fileKeyList = array_keys($docTester->getLibFilesTest('product', 1));
    sort($fileKeyList, SORT_NUMERIC);
    return $fileKeyList;
};

$fileIdList = $getFileKeyList();
r(count($fileIdList))        && p() && e('4'); // 测试从文档当前版本移除的附件不在附件库中附件数
r(implode(',', $fileIdList)) && p() && e('102,103,105,106'); // 测试从文档当前版本移除的附件不在附件库中显示

/* 恢复文档2后，附件104应恢复显示。 */
$tester->dao->update(TABLE_DOC)->set('deleted')->eq(0)->where('id')->eq(2)->exec();
$fileIdList = $getFileKeyList();
r(count($fileIdList))        && p() && e('5'); // 测试恢复文档后附件在附件库中附件数
r(implode(',', $fileIdList)) && p() && e('102,103,104,105,106'); // 测试恢复文档后附件在附件库中恢复显示

/* 将附件105从文档3的当前版本移除。 */
$docTester->updateDocFileTest(3, 105);
$fileIdList = $getFileKeyList();
r(count($fileIdList)) && p() && e('4'); // 测试再次从文档当前版本移除附件后附件库中附件数
r(implode(',', $fileIdList)) && p() && e('102,103,104,106'); // 测试再次从文档当前版本移除附件后附件库同步隐藏
