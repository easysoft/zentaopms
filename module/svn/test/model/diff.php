#!/usr/bin/env php
<?php

/**

title=测试 svnModel::diff();
timeout=0
cid=18714

- 步骤1：匹配仓库URL且版本号为1时返回非空diff结果 @1
- 步骤2：匹配仓库URL且版本号为0时返回非空diff结果 @1
- 步骤3：匹配仓库URL且版本号为负数时返回非空diff结果 @1
- 步骤4：不匹配任何仓库URL时返回false @1
- 步骤5：空URL时返回false @1
- 步骤6：带空格编码的仓库URL时返回非空diff结果 @1
- 步骤7：超大版本号时返回非空diff结果 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$dao = $tester->dao;

$dao->delete()->from(TABLE_REPO)->exec();
$dao->insert(TABLE_REPO)->data(array(
    'id'       => 201,
    'product'  => 1,
    'name'     => 'repo201',
    'path'     => 'https://example.com/svn/repo',
    'SCM'      => 'Subversion',
    'client'   => 'svn',
    'encoding' => 'utf-8',
    'account'  => '',
    'password' => '',
    'synced'   => 1,
    'deleted'  => 0
))->exec();

$svnTest = new svnModelTest();

r($svnTest->diffTest('https://example.com/svn/repo', 1)) && p('hasContent') && e('1');
r($svnTest->diffTest('https://example.com/svn/repo', 0)) && p('hasContent') && e('1');
r($svnTest->diffTest('https://example.com/svn/repo', -1)) && p('hasContent') && e('1');
r($svnTest->diffTest('http://nonexistent.url', 1)) && p('isFalse') && e('1');
r($svnTest->diffTest('', 1)) && p('isFalse') && e('1');
r($svnTest->diffTest('https://example.com/svn/repo/file%20with%20spaces', 1)) && p('hasContent') && e('1');
r($svnTest->diffTest('https://example.com/svn/repo', 999999)) && p('hasContent') && e('1');
