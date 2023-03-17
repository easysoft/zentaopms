#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->updateLinkedBug();
cid=1
pid=1

项目版本修改链接bug >> user10
执行版本修改链接bug >> 1
不设置bugID >> 0

*/
$buildID    = array('1', '11', '12');
$bugs       = array('311', '301');
$moreBugs   = array('312', '302');
$resolvedBy = array($bugs[0] => 'admin', $bugs[1] => 'user10');

$normalLink   = array('bugs' => $bugs, 'resolvedBy' => $resolvedBy);
$noResolvedBy = array('bugs' => $moreBugs);
$noBugs       = array('resolvedBy' => $resolvedBy);

$build = new buildTest();

$projectLink      = $build->updateLinkedBugTest($buildID[0],$normalLink);
$executionLink    = $build->updateLinkedBugTest($buildID[1],$normalLink);
$noResolvedByLink = $build->updateLinkedBugTest($buildID[2],$noResolvedBy);
$noBugs           = $build->updateLinkedBugTest($buildID[0],$noBugs);
//a($noBugs);die;
r($projectLink)      && p('301:resolvedBy')    && e('user10'); //项目版本修改链接bug
r($executionLink)    && p('311:resolvedBuild') && e('1');      //执行版本修改链接bug
r($noResolvedByLink) && p('302:resolvedBy')    && e('');       //不设置解决人
r($noBugs)           && p()                    && e('0');      //不设置bugID

