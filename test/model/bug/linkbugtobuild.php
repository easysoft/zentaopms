#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=测试bugModel->linkBugToBuild();
cid=1
pid=1

把bug 1 2关联到build 1 >> 1,2
把bug 3 4关联到build 3 >> 3,4
把bug 5 6关联到build 5 >> 5,6
把bug 5 6关联到build 1 >> 1,2,7,8
把bug 5 6关联到build 1 >> 1,2,7,8,9,10

*/

$bugIDList1 = array('1', '2');
$bugIDList2 = array('3', '4');
$bugIDList3 = array('5', '6');
$bugIDList4 = array('7', '8');
$bugIDList5 = array('9', '10');

$buildList = array('1', '3', '5');

$bug = new bugTest();
r($bug->linkBugToBuildTest($bugIDList1, $buildList[0])) && p('bugs')  && e('1,2');   // 把bug 1 2关联到build 1
r($bug->linkBugToBuildTest($bugIDList2, $buildList[1])) && p('bugs')  && e('3,4');   // 把bug 3 4关联到build 3
r($bug->linkBugToBuildTest($bugIDList3, $buildList[2])) && p('bugs')  && e('5,6');   // 把bug 5 6关联到build 5
r($bug->linkBugToBuildTest($bugIDList4, $buildList[0])) && p('bugs')  && e('1,2,7,8');   // 把bug 5 6关联到build 1
r($bug->linkBugToBuildTest($bugIDList5, $buildList[0])) && p('bugs')  && e('1,2,7,8,9,10');   // 把bug 5 6关联到build 1
