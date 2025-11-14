#!/usr/bin/env php
<?php

/**

title=测试 repoModel::createActionChanges();
timeout=0
cid=18036

- 测试步骤1：SVN单个文件修改第0条的field属性 @subversion
- 测试步骤2：Git多种操作类型第0条的field属性 @git
- 测试步骤3：空文件变更 @0
- 测试步骤4：无效SCM类型第0条的field属性 @git
- 测试步骤5：SVN处理A操作第0条的field属性 @subversion
- 测试步骤6：边界值null文件列表 @0
- 测试步骤7：验证Git类型返回第0条的field属性 @git

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

su('admin');

$repo = new repoTest();

// 测试数据1：SVN单个文件修改
$log1 = new stdclass();
$log1->revision = '12345';
$log1->files = array('M' => array('/src/model.php'));

// 测试数据2：Git多种操作类型
$log2 = new stdclass();
$log2->revision = 'abc123def456';
$log2->files = array(
    'A' => array('/new/file.php'),
    'M' => array('/modified/file.js'),
    'D' => array('/deleted/old.css')
);

// 测试数据3：空文件列表
$log3 = new stdclass();
$log3->revision = 'empty123';
$log3->files = array();

// 测试数据4：无效SCM类型
$log4 = new stdclass();
$log4->revision = 'invalid456';
$log4->files = array('M' => array('/test/file.php'));

// 测试数据5：对比SVN和Git
$log5 = new stdclass();
$log5->revision = 'compare789';
$log5->files = array('A' => array('/new/compare.php'));

// 测试数据6：null文件列表（边界值）
$log6 = new stdclass();
$log6->revision = 'boundary123';
// 不设置files属性，模拟null情况

// 测试数据7：验证M操作的diff链接
$log7 = new stdclass();
$log7->revision = 'difftest999';
$log7->files = array('M' => array('/test/diff.php'));

r($repo->createActionChangesTest($log1, '/repo/root/', 'svn')) && p('0:field') && e('subversion'); // 测试步骤1：SVN单个文件修改
r($repo->createActionChangesTest($log2, '/repo/root/', 'git')) && p('0:field') && e('git'); // 测试步骤2：Git多种操作类型
r($repo->createActionChangesTest($log3, '/repo/root/', 'git')) && p() && e('0'); // 测试步骤3：空文件变更
r($repo->createActionChangesTest($log4, '/repo/root/', 'invalidscm')) && p('0:field') && e('git'); // 测试步骤4：无效SCM类型
r($repo->createActionChangesTest($log5, '/repo/root/', 'svn')) && p('0:field') && e('subversion'); // 测试步骤5：SVN处理A操作
r($repo->createActionChangesTest($log6, '/repo/root/', 'git')) && p() && e('0'); // 测试步骤6：边界值null文件列表
r($repo->createActionChangesTest($log7, '/repo/root/', 'git')) && p('0:field') && e('git'); // 测试步骤7：验证Git类型返回