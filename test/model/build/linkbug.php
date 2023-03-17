#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->linkBug();
cid=1
pid=1

项目版本链接bug >> ,311,301,11
执行版本链接bug >> ,311,301,101
不传bugID >> ,311,301,

*/

$buildIDList = array('1', '11');
$bugs        = array('311', '301');
$resolvedBy  = array($bugs[0] => 'admin', $bugs[1] => 'dev10');

$nomalBuglink = array('bugs' => $bugs, 'resolvedBy' => $resolvedBy);
$noBuglink    = array('bugs' => array());

$build = new buildTest();

r($build->linkBugTest($buildIDList[0], $nomalBuglink)) && p('1:bugs,project')    && e(',311,301,11'); //项目版本链接bug
r($build->linkBugTest($buildIDList[1], $nomalBuglink)) && p('11:bugs,execution') && e(',311,301,101');//执行版本链接bug
r($build->linkBugTest($buildIDList[0], $noBuglink))    && p('1:bugs')            && e(',311,301,');   //不传bugID
//
