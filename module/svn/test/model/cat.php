#!/usr/bin/env php
<?php

/**

title=测试 svnModel::cat();
timeout=0
cid=18712

- 执行svnTest模块的catTest方法，参数是$validUrl, $validRevision  @0
- 执行svnTest模块的catTest方法，参数是$invalidUrl, $validRevision  @0
- 执行svnTest模块的catTest方法，参数是$validUrl, $invalidRevision  @0
- 执行svnTest模块的catTest方法，参数是$validUrl, $zeroRevision  @0
- 执行svnTest模块的catTest方法，参数是$validUrl, $negativeRevision  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 开始输出缓冲来捕获ZenData输出
ob_start();

// 直接创建测试数据
$table = zenData('repo');
$table->id->range('1-3');
$table->product->range('1-3');
$table->name->range('svn-repo-1,svn-repo-2,svn-repo-3');
$table->path->range('https://svn.qc.oop.cc/svn/unittest,https://svn.example.com/repo,file:///var/svn/local');
$table->SCM->range('Subversion');
$table->client->range('svn');
$table->deleted->range('0');
$table->encoding->range('utf-8');
$table->gen(3);

// 清理ZenData输出
ob_get_clean();

su('admin');

$svnTest = new svnModelTest();

// 测试步骤1：使用有效URL和版本号获取文件内容
$validUrl = 'https://svn.qc.oop.cc/svn/unittest/README';
$validRevision = 2;
r($svnTest->catTest($validUrl, $validRevision)) && p() && e('0');

// 测试步骤2：使用无效URL测试错误处理
$invalidUrl = 'http://invalid.domain/svn/repo/unit';
r($svnTest->catTest($invalidUrl, $validRevision)) && p() && e('0');

// 测试步骤3：使用不存在的版本号测试边界情况
$invalidRevision = 999;
r($svnTest->catTest($validUrl, $invalidRevision)) && p() && e('0');

// 测试步骤4：使用零版本号测试边界值
$zeroRevision = 0;
r($svnTest->catTest($validUrl, $zeroRevision)) && p() && e('0');

// 测试步骤5：使用负数版本号测试异常输入
$negativeRevision = -1;
r($svnTest->catTest($validUrl, $negativeRevision)) && p() && e('0');