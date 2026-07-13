#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::getDiffs();
timeout=0
cid=0

- 执行ppmModel模块的getDiffsTest方法  @0
- 执行ppmModel模块的getDiffsTest方法  @0
- 执行ppmModel模块的getDiffsTest方法 第0条的fileName属性 @test.txt
- 执行ppmModel模块的getDiffsTest方法 第0条的fileName属性 @file.php
- 执行ppmModel模块的getDiffsTest方法  @1
- 执行ppmModel模块的getDiffsTest方法  @2
- 执行ppmModel模块的getDiffsTest方法 第0条的fileName属性 @old.php
- 执行ppmModel模块的getDiffsTest方法  @1
- 执行ppmModel模块的getDiffsTest方法  @0
- 执行ppmModel模块的getDiffsTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->gen(1);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('6101');
$repo->spaceID->range('1');
$repo->product->range('1');
$repo->name->range('ppm-repo-6101');
$repo->scmType->range('git');
$repo->acl->range('private');
$repo->createdBy->range('admin');
$repo->gen(1);

su('admin');

$ppmModel = new ppmModelTest();
$gitfox   = $ppmModel->instance->loadModel('gitfox');
$reflect  = new ReflectionObject($gitfox);
$repos    = $reflect->getProperty('repos');
$repos->setAccessible(true);
$cache = $repos->getValue($gitfox);
$cache[6101] = (object)array('id' => 6101, 'path' => 'space/ppm-repo-6101', 'gitURL' => 'http://gitfox.local/space/ppm-repo-6101.git');
$repos->setValue($gitfox, $cache);

$textDiff = "diff --git a/test.txt b/test.txt\nindex 123..456 100644\n--- a/test.txt\n+++ b/test.txt\n@@ -1 +1,2 @@\n test\n+new line";
$phpDiff  = "diff --git a/file.php b/file.php\n+++ b/file.php\n@@ -1,1 +1,2 @@\n <?php\n+echo 'test';";
$multiDiff = "diff --git a/file1.php b/file1.php\n+++ b/file1.php\n@@ -1,1 +1,2 @@\n <?php\n+echo 'test1';\ndiff --git a/file2.php b/file2.php\n+++ b/file2.php\n@@ -1,1 +1,2 @@\n <?php\n+echo 'test2';";
$deleteDiff = "diff --git a/old.php b/old.php\ndeleted file mode 100644\nindex 123..000\n--- a/old.php\n+++ /dev/null\n@@ -1 +0,0 @@\n-<?php";
$renameDiff = "diff --git a/old.php b/renamed.php\nsimilarity index 100%\nrename from old.php\nrename to renamed.php";
$emptyDiff  = "";

r($ppmModel->getDiffsTest((object)array('diffs' => $textDiff))) && p() && e('0');
r($ppmModel->getDiffsTest((object)array('repoID' => 9999, 'diffs' => $textDiff))) && p() && e('0');
r($ppmModel->getDiffsTest((object)array('repoID' => 6101, 'diffs' => $textDiff))) && p('0:fileName') && e('test.txt');
r($ppmModel->getDiffsTest((object)array('repoID' => 6101, 'diffs' => $phpDiff))) && p('0:fileName') && e('file.php');
r(count($ppmModel->getDiffsTest((object)array('repoID' => 6101, 'diffs' => $phpDiff)))) && p() && e('1');
r(count($ppmModel->getDiffsTest((object)array('repoID' => 6101, 'diffs' => $multiDiff)))) && p() && e('2');
r($ppmModel->getDiffsTest((object)array('repoID' => 6101, 'diffs' => $deleteDiff))) && p('0:fileName') && e('old.php');
r(count($ppmModel->getDiffsTest((object)array('repoID' => 6101, 'diffs' => $renameDiff)))) && p() && e('1');
r($ppmModel->getDiffsTest((object)array('repoID' => 6101, 'diffs' => $emptyDiff))) && p() && e('0');
r($ppmModel->getDiffsTest((object)array('repoID' => 0, 'diffs' => $textDiff))) && p() && e('0');