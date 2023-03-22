#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/projectrelease.class.php';
su('admin');

/**

title=测试 projectreleaseModel->linkBug();
cid=1
pid=1

测试normal状态的发布，releaseID正常存在,type为bug >> ,1,2
测试normal状态的发布，releaseID正常存在,type为leftBug >> ,1,2
测试normal状态的发布，releaseID正常存在,type为空 >> ,1,2,
测试terminate状态的发布，releaseID正常存在,type为bug >> ,1,2
测试terminate状态的发布，releaseID正常存在,type为leftBug >> ,1,2
测试terminate状态的发布，releaseID正常存在,type为空 >> ,1,2,

*/
$releaseID = array(1, 10);
$type      = array('bug', 'leftBug', '');

$projectrelease = new projectreleaseTest();

r($projectrelease->linkBugTest($releaseID[0],$type[0])) && p('bugs')     && e(',1,2');  //测试normal状态的发布，releaseID正常存在,type为bug
r($projectrelease->linkBugTest($releaseID[0],$type[1])) && p('leftBugs') && e(',1,2');  //测试normal状态的发布，releaseID正常存在,type为leftBug
r($projectrelease->linkBugTest($releaseID[0],$type[2])) && p('leftBugs') && e(',1,2,'); //测试normal状态的发布，releaseID正常存在,type为空
r($projectrelease->linkBugTest($releaseID[1],$type[0])) && p('bugs')     && e(',1,2');  //测试terminate状态的发布，releaseID正常存在,type为bug
r($projectrelease->linkBugTest($releaseID[1],$type[1])) && p('leftBugs') && e(',1,2');  //测试terminate状态的发布，releaseID正常存在,type为leftBug
r($projectrelease->linkBugTest($releaseID[1],$type[2])) && p('leftBugs') && e(',1,2,'); //测试terminate状态的发布，releaseID正常存在,type为空
