#!/usr/bin/env php
<?php

/**

title=测试 docModel->renameFile();
timeout=0
cid=0

- 执行$notFound @1
- 执行$unmatched @1
- 执行$typeMismatch @1
- 执行$tooLong @1
- 执行$renamed @1
- 执行$result->extension @txt
- 执行$renamedWithExtension @1
- 执行$result->actionExists @1
- 执行$history @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
su('admin');

/* 清空相关数据表，使用固定数据构造测试场景。 */
zenData('doc')->gen(0);
zenData('file')->gen(0);
zenData('action')->gen(0);
zenData('history')->gen(0);

$now = helper::now();

/* 构造文档 1、文档 2。 */
$tester->dao->insert(TABLE_DOC)->set('id')->eq(1)->set('product')->eq(1)->set('lib')->eq(1)->set('title')->eq('文档1')->set('type')->eq('text')->set('status')->eq('normal')->set('version')->eq(1)->set('deleted')->eq(0)->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(2)->set('product')->eq(1)->set('lib')->eq(1)->set('title')->eq('文档2')->set('type')->eq('text')->set('status')->eq('normal')->set('version')->eq(1)->set('deleted')->eq(0)->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();

/* 构造附件：附件1、附件2 分别属于文档1、文档2，附件3 属于任务。 */
$tester->dao->insert(TABLE_FILE)->set('id')->eq(1)->set('pathname')->eq('2026/11.txt')->set('title')->eq('附件1')->set('extension')->eq('txt')->set('size')->eq(100)->set('objectType')->eq('doc')->set('objectID')->eq(1)->set('deleted')->eq(0)->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_FILE)->set('id')->eq(2)->set('pathname')->eq('2026/12.txt')->set('title')->eq('附件2')->set('extension')->eq('txt')->set('size')->eq(100)->set('objectType')->eq('doc')->set('objectID')->eq(2)->set('deleted')->eq(0)->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_FILE)->set('id')->eq(3)->set('pathname')->eq('2026/13.txt')->set('title')->eq('任务附件')->set('extension')->eq('txt')->set('size')->eq(100)->set('objectType')->eq('task')->set('objectID')->eq(1)->set('deleted')->eq(0)->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();

$docTester = new docModelTest();

/* 步骤1：fileID 不存在，应返回未找到文件。 */
$result = $docTester->renameFileTest(1, 999, '新名称');
$notFound = ($result->result == 'fail' && $result->notFound == 1) ? '1' : '0';
r($notFound) && p() && e('1');

/* 步骤2：附件2 属于文档2，用文档1调用应失败，且附件标题不变。 */
$result = $docTester->renameFileTest(1, 2, '越权重命名');
$unmatched = ($result->result == 'fail' && $result->title == '附件2') ? '1' : '0';
r($unmatched) && p() && e('1');

/* 步骤3：附件3 的 objectType 为 task，不符合 doc 归属，应失败且标题不变。 */
$result = $docTester->renameFileTest(1, 3, '类型不符');
$typeMismatch = ($result->result == 'fail' && $result->title == '任务附件') ? '1' : '0';
r($typeMismatch) && p() && e('1');

/* 步骤4：文件名超过 80 字符，应返回长度错误且标题不变。 */
$result = $docTester->renameFileTest(1, 1, str_repeat('a', 81));
$tooLong = ($result->result == 'fail' && $result->lengthError == 1 && $result->title == '附件1') ? '1' : '0';
r($tooLong) && p() && e('1');

/* 步骤5：正常重命名（不带扩展名），标题更新且扩展名保持原值。 */
$result = $docTester->renameFileTest(1, 1, '附件1-新');
$renamed = ($result->result == 'success' && $result->title == '附件1-新') ? '1' : '0';
r($renamed) && p() && e('1');
r($result->extension) && p() && e('txt');

/* 步骤6：正常重命名（带扩展名），标题与扩展名同时更新。 */
$result = $docTester->renameFileTest(1, 1, '附件1-新-改', 'md');
$renamedWithExtension = ($result->result == 'success' && $result->title == '附件1-新-改' && $result->extension == 'md') ? '1' : '0';
r($renamedWithExtension) && p() && e('1');

/* 步骤7：成功重命名后生成 editfile 操作日志，并记录 fileName 的变更历史。 */
r($result->actionExists) && p() && e('1');
$history = ($result->oldName == '附件1-新' && $result->newName == '附件1-新-改') ? '1' : '0';
r($history) && p() && e('1');