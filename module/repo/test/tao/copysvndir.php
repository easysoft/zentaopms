#!/usr/bin/env php
<?php

/**

title=测试 repoTao::copySvnDir();
timeout=0
cid=18115

- 测试正常SVN目录复制 @1
- 测试源路径不存在时的复制 @0
- 测试复制到已存在的目录 @1
- 测试复制版本过小的情况 @1
- 测试无效repo ID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$repoTable = zenData('repo');
$repoTable->id->range('1-5');
$repoTable->name->range('repo1,repo2,repo3,repo4,repo5');
$repoTable->gen(5);

$historyTable = zenData('repohistory');
$historyTable->id->range('1-10');
$historyTable->repo->range('1{8},2{2}');
$historyTable->revision->range('1,2,3,4,5,6,7,8,1,2');
$historyTable->gen(10);

$filesTable = zenData('repofiles');
$filesTable->id->range('1-15');
$filesTable->repo->range('1{12},2{3}');
$filesTable->revision->range('1{3},2{3},3{3},4{3},5{3}');
$filesTable->path->range('/trunk/src/main.c,/trunk/src/util.c,/trunk/src/config.h,/trunk/docs/readme.txt,/trunk/docs/manual.pdf,/trunk/test/test1.c,/trunk/src/db.c,/trunk/src/cache.c,/trunk/src/logger.c,/trunk/lib/helper.c,/trunk/lib/parser.c,/trunk/lib/validator.c,/other/file1.txt,/other/file2.txt,/other/file3.txt');
$filesTable->parent->range('/trunk/src{9},/trunk/docs{2},/trunk/test,/trunk/lib{3}');
$filesTable->type->range('file');
$filesTable->action->range('A');
$filesTable->gen(15);

su('admin');

$repoTest = new repoTaoTest();

r($repoTest->copySvnDirTest(1, '/trunk/src', '5', '/branches/dev/src')) && p() && e('1'); // 测试正常SVN目录复制
r($repoTest->copySvnDirTest(1, '/nonexist', '5', '/branches/test')) && p() && e('0'); // 测试源路径不存在时的复制
r($repoTest->copySvnDirTest(1, '/trunk/src', '5', '/trunk/src')) && p() && e('1'); // 测试复制到已存在的目录
r($repoTest->copySvnDirTest(1, '/trunk/src', '3', '/branches/early')) && p() && e('1'); // 测试复制版本过小的情况
r($repoTest->copySvnDirTest(999, '/empty', '10', '/branches/empty')) && p() && e('0'); // 测试无效repo ID