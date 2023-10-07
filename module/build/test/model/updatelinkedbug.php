#!/usr/bin/env php
<?php
/**

title=测试 buildModel->updateLinkedBug();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('build')->config('build')->gen(20);
zdTable('bug')->config('bug')->gen(20);
zdTable('user')->gen(20);
su('admin');

$buildID    = array(1, 10, 12);
$bugs       = array(1, 10);
$moreBugs   = array(2, 12);
$resolvedBy = array($bugs[0] => 'admin', $bugs[1] => 'user10');

$normalLink   = array('bugs'       => $bugs, 'resolvedBy' => $resolvedBy);
$noResolvedBy = array('bugs'       => $moreBugs);
$noBugs       = array('resolvedBy' => $resolvedBy);

$build = new buildTest();
r($build->updateLinkedBugTest($buildID[0],$normalLink))   && p('10:resolvedBy')   && e('user10'); // 项目版本修改链接bug
r($build->updateLinkedBugTest($buildID[1],$normalLink))   && p('1:resolvedBuild') && e('1');      // 执行版本修改链接bug
r($build->updateLinkedBugTest($buildID[2],$noResolvedBy)) && p('2:resolvedBy')    && e('~~');     // 不设置解决人
r($build->updateLinkedBugTest($buildID[0],$noBugs))       && p()                  && e('0');      // 不设置bugID
