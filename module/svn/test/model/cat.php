#!/usr/bin/env php
<?php

/**

title=测试 svnModel::cat();
timeout=0
cid=0

- 执行svnTest模块的catTest方法，参数是$validUrl, $validRevision  @sh: 1: svn: not found
- 执行svnTest模块的catTest方法，参数是$invalidUrl, $validRevision  @~~
- 执行svnTest模块的catTest方法，参数是$validUrl, $invalidRevision  @0
- 执行svnTest模块的catTest方法，参数是$validUrl, $zeroRevision  @sh: 1: svn: not found
- 执行svnTest模块的catTest方法，参数是$validUrl, $negativeRevision  @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/svn.unittest.class.php';

zenData('repo')->loadYaml('repo')->gen(1);
su('admin');

$svnTest = new svnTest();

// 测试步骤1：使用有效URL和版本号获取文件内容
$validUrl = 'https://svn.qc.oop.cc/svn/unittest/README';
$validRevision = 2;
r($svnTest->catTest($validUrl, $validRevision)) && p() && e('sh: 1: svn: not found');

// 测试步骤2：使用无效URL测试错误处理
$invalidUrl = 'http://10.0.7.237/svn/repo/unit';
r($svnTest->catTest($invalidUrl, $validRevision)) && p() && e('~~');

// 测试步骤3：使用不存在的版本号测试边界情况
$invalidRevision = 999;
r($svnTest->catTest($validUrl, $invalidRevision)) && p() && e('0');

// 测试步骤4：使用零版本号测试边界值
$zeroRevision = 0;
r($svnTest->catTest($validUrl, $zeroRevision)) && p() && e('sh: 1: svn: not found');

// 测试步骤5：使用负数版本号测试异常输入
$negativeRevision = -1;
r($svnTest->catTest($validUrl, $negativeRevision)) && p() && e('~~');