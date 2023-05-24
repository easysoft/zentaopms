#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('bug')->config('bug_batchupdate')->gen(10);
zdTable('user')->gen(1);
zdTable('action')->gen(1);
zdTable('score')->gen(1);

su('admin');

/**

title=测试bugModel->batchUpdate();
cid=1
pid=1

*/

$normal = new stdclass();
$normal->id         = 1;
$normal->severity   = 1;
$normal->title      = '修改后的名称1';
$normal->resolution = '';
$normal->plan       = 1;

$resolved1 = new stdclass();
$resolved1->id         = 2;
$resolved1->severity   = 1;
$resolved1->title      = '修改后的名称2';
$resolved1->resolution = 'fixed';
$resolved1->status     = 'resolved';
$resolved1->resolvedBy = 'admin';
$resolved1->plan       = 0;

$resolved3 = new stdclass();
$resolved3->id         = 3;
$resolved3->severity   = 3;
$resolved3->title      = '修改后的名称3';
$resolved3->resolution = 'fixed';
$resolved3->status     = 'resolved';
$resolved3->resolvedBy = 'admin';
$resolved3->plan       = 0;

$hasResolved1 = new stdclass();
$hasResolved1->id         = 4;
$hasResolved1->severity   = 1;
$hasResolved1->title      = '修改后的名称4';
$hasResolved1->status     = 'resolved';
$hasResolved1->resolution = 'fixed';
$hasResolved1->resolvedBy = 'admin';
$hasResolved1->plan         = 0;

$hasResolved3 = new stdclass();
$hasResolved3->id         = 5;
$hasResolved3->severity   = 3;
$hasResolved3->title      = '修改后的名称5';
$hasResolved3->status     = 'resolved';
$hasResolved3->resolution = 'fixed';
$hasResolved3->resolvedBy = 'admin';
$hasResolved3->plan         = 0;

$hasResolution = new stdclass();
$hasResolution->id         = 6;
$hasResolution->title      = '有解决方案';
$hasResolution->resolvedBy = 'admin';
$hasResolution->resolution = 'fixed';
$hasResolution->plan         = 2;

$hasDuplicateBug = new stdclass();
$hasDuplicateBug->id           = 7;
$hasDuplicateBug->title        = '有重复bug';
$hasDuplicateBug->resolution   = 'duplicate';
$hasDuplicateBug->duplicateBug = 1;
$hasDuplicateBug->plan         = 1;

$noTitle = new stdclass();
$noTitle->id         = 8;
$noTitle->title      = '';
$noTitle->resolution = '';

$noResolution = new stdclass();
$noResolution->id         = 9;
$noResolution->title      = '没有解决方案';
$noResolution->resolvedBy = 'admin';
$noResolution->resolution = '';

$noDuplicateBug = new stdclass();
$noDuplicateBug->id           = 10;
$noDuplicateBug->title        = '没有重复bug';
$noDuplicateBug->resolution   = 'duplicate';
$noDuplicateBug->duplicateBug = 0;

$titleErrorBugs      = array($normal->id => $normal, $noTitle->id => $noTitle);
$resolutionErrorBugs = array($hasResolution->id => $hasResolution, $noResolution->id => $noResolution);
$duplicateErrorBugs  = array($hasDuplicateBug->id => $hasDuplicateBug, $noDuplicateBug->id => $noDuplicateBug);
$allErrorBugs        = array($noTitle->id => $noTitle, $noResolution->id => $noResolution, $noDuplicateBug->id => $noDuplicateBug);
$normalBugs          = array($normal->id => $normal, $resolved1->id => $resolved1, $resolved3->id => $resolved3);
$hasResolvedBugs     = array($resolved1->id => $resolved1, $resolved3->id => $resolved3);

$bug = new bugTest();
r($bug->batchUpdateObject($titleErrorBugs))      && p() && e('title[8]:『Bug标题』不能为空。');                                                                           // 测试批量修改 bugs 中 有标题为空的 bug
r($bug->batchUpdateObject($resolutionErrorBugs)) && p() && e('resolution[9]:『解决方案』不能为空。');                                                                     // 测试批量修改 bugs 中 有解决的bug但解决方案为空的 bug
r($bug->batchUpdateObject($duplicateErrorBugs))  && p() && e('duplicateBug[10]:『重复Bug』不能为空。');                                                                   // 测试批量修改 bugs 中 有解决方案为重复bug的bug但重复bug为空的 bug
r($bug->batchUpdateObject($allErrorBugs))        && p() && e('title[8]:『Bug标题』不能为空。resolution[9]:『解决方案』不能为空。duplicateBug[10]:『重复Bug』不能为空。'); // 测试批量修改 bugs 中 有标题为空的 bug 有解决的bug但解决方案为空的 bug 有解决方案为重复bug的bug但重复bug为空的 bug
r($bug->batchUpdateObject($normalBugs))          && p() && e('scoreDifference:26;titles:修改后的名称1,修改后的名称2,修改后的名称3');                                      // 测试批量修改 bugs
r($bug->batchUpdateObject($hasResolvedBugs))     && p() && e('scoreDifference:0;titles:修改后的名称2,修改后的名称3');                                                     // 测试批量修改 已经解决bugs
