#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->linkBug();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/release.class.php';

$release = zdTable('release')->config('release');
$release->bugs->range('1-5');
$release->leftBugs->range('1-5');
$release->gen(5);

zdTable('bug')->config('bug')->gen(5);
zdTable('user')->gen(5);
su('admin');

$releaseID = array(0, 1, 6);
$types     = array('bug', 'leftBug');
$bugs[0]   = array(2, 3);
$bugs[1]   = array();

$releaseTester = new releaseTest();
r($releaseTester->linkBugTest($releaseID[0], $types[0], $bugs[0])) && p()                   && e('0');            // 测试releaseID为0时，关联bug
r($releaseTester->linkBugTest($releaseID[1], $types[0], $bugs[0])) && p('0:old;0:new', ';') && e('1;1,2,3');      // 测试releaseID为1时，关联bug
r($releaseTester->linkBugTest($releaseID[2], $types[0], $bugs[0])) && p()                   && e('0');            // 测试releaseID不存在时，关联bug
r($releaseTester->linkBugTest($releaseID[0], $types[0], $bugs[1])) && p()                   && e('0');            // 测试releaseID=0，bug为空时，关联bug
r($releaseTester->linkBugTest($releaseID[1], $types[0], $bugs[1])) && p('0:old;0:new', ';') && e('1,2,3;1,2,3,'); // 测试releaseID=1，bug为空时，关联bug
r($releaseTester->linkBugTest($releaseID[2], $types[0], $bugs[1])) && p()                   && e('0');            // 测试releaseID不存在，bug为空时，关联bug
r($releaseTester->linkBugTest($releaseID[0], $types[1], $bugs[0])) && p()                   && e('0');            // 测试releaseID为0时，关联遗留的bug
r($releaseTester->linkBugTest($releaseID[1], $types[1], $bugs[0])) && p('0:old;0:new', ';') && e('1;1,2,3');      // 测试releaseID为1时，关联遗留的bug
r($releaseTester->linkBugTest($releaseID[2], $types[1], $bugs[0])) && p()                   && e('0');            // 测试releaseID不存在时，关联遗留的bug
r($releaseTester->linkBugTest($releaseID[0], $types[1], $bugs[1])) && p()                   && e('0');            // 测试releaseID=0，bug为空时，关联遗留的bug
r($releaseTester->linkBugTest($releaseID[1], $types[1], $bugs[1])) && p('0:old;0:new', ';') && e('1,2,3;1,2,3,'); // 测试releaseID=1，bug为空时，关联遗留的bug
r($releaseTester->linkBugTest($releaseID[2], $types[1], $bugs[1])) && p()                   && e('0');            // 测试releaseID不存在，bug为空时，关联遗留的bug1
