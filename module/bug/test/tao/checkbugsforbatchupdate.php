#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

/**

title=bugTao->checkRequired4Resolve();
timeout=0
cid=1


*/

$hasTitle = new stdclass();
$hasTitle->id         = 1;
$hasTitle->title      = 'hasTitle';
$hasTitle->resolution = '';

$hasResolution = new stdclass();
$hasResolution->id         = 2;
$hasResolution->title      = 'hasResolution';
$hasResolution->resolvedBy = 'admin';
$hasResolution->resolution = 'fixed';

$hasDuplicateBug = new stdclass();
$hasDuplicateBug->id           = 3;
$hasDuplicateBug->title        = 'hasDuplicateBug';
$hasDuplicateBug->resolution   = 'duplicate';
$hasDuplicateBug->duplicateBug = 1;

$noTitle = new stdclass();
$noTitle->id         = 4;
$noTitle->title      = '';
$noTitle->resolution = '';

$noResolution = new stdclass();
$noResolution->id         = 5;
$noResolution->title      = 'noResolution';
$noResolution->resolvedBy = 'admin';
$noResolution->resolution = '';

$noDuplicateBug = new stdclass();
$noDuplicateBug->id           = 6;
$noDuplicateBug->title        = 'noDuplicateBug';
$noDuplicateBug->resolution   = 'duplicate';
$noDuplicateBug->duplicateBug = 0;

$bugs1  = array($hasTitle);
$bugs2  = array($hasResolution);
$bugs3  = array($hasDuplicateBug);
$bugs4  = array($hasTitle, $hasResolution, $hasDuplicateBug);
$bugs5  = array($noTitle);
$bugs6  = array($noResolution);
$bugs7  = array($noDuplicateBug);
$bugs8  = array($noTitle, $noResolution, $noDuplicateBug);
$bugs9  = array($hasTitle, $noTitle);
$bugs10 = array($hasResolution, $noResolution);
$bugs11 = array($hasDuplicateBug, $noDuplicateBug);
$bugs12 = array($hasTitle, $hasResolution, $hasDuplicateBug, $noTitle, $noResolution, $noDuplicateBug);

$bug = new bugTest();

r($bug->checkBugsForBatchUpdateTest($bugs1))  && p() && e('no error');                                                                                                // 检查 有名称 的bug是否符合要求
r($bug->checkBugsForBatchUpdateTest($bugs2))  && p() && e('no error');                                                                                                // 检查 有解决方案 的bug是否符合要求
r($bug->checkBugsForBatchUpdateTest($bugs3))  && p() && e('no error');                                                                                                // 检查 有重复bug 的bug是否符合要求
r($bug->checkBugsForBatchUpdateTest($bugs4))  && p() && e('no error');                                                                                                // 检查 有名称 有解决方案 有重复bug 的bug是否符合要求
r($bug->checkBugsForBatchUpdateTest($bugs5))  && p() && e('title[4]:『Bug标题』不能为空。');                                                                          // 检查 无名称 的bug是否符合要求
r($bug->checkBugsForBatchUpdateTest($bugs6))  && p() && e('resolution[5]:『解决方案』不能为空。');                                                                    // 检查 无解决方案 的bug是否符合要求
r($bug->checkBugsForBatchUpdateTest($bugs7))  && p() && e('duplicateBug[6]:『重复Bug』不能为空。');                                                                   // 检查 无重复bug 的bug是否符合要求
r($bug->checkBugsForBatchUpdateTest($bugs8))  && p() && e('title[4]:『Bug标题』不能为空。resolution[5]:『解决方案』不能为空。duplicateBug[6]:『重复Bug』不能为空。'); // 检查 无名称 无解决方案 无重复bug 的bug是否符合要求
r($bug->checkBugsForBatchUpdateTest($bugs9))  && p() && e('title[4]:『Bug标题』不能为空。');                                                                          // 检查 有名称 无名称 的bug是否符合要求
r($bug->checkBugsForBatchUpdateTest($bugs10)) && p() && e('resolution[5]:『解决方案』不能为空。');                                                                    // 检查 有解决方案 无解决方案 的bug是否符合要求
r($bug->checkBugsForBatchUpdateTest($bugs11)) && p() && e('duplicateBug[6]:『重复Bug』不能为空。');                                                                   // 检查 有重复bug 无重复bug 的bug是否符合要求
r($bug->checkBugsForBatchUpdateTest($bugs12)) && p() && e('title[4]:『Bug标题』不能为空。resolution[5]:『解决方案』不能为空。duplicateBug[6]:『重复Bug』不能为空。'); // 检查 有名称 有解决方案 有重复bug 无名称 无解决方案 无重复bug 的bug是否符合要求
