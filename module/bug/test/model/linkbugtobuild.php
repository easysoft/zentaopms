#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('bug')->gen(10);
zdTable('user')->gen(1);
zdTable('build')->gen(10);
zdTable('release')->config('release')->gen(10);

su('admin');

/**

title=测试bugModel->linkBugToBuild();
cid=1
pid=1

*/

$bugIDList = array(1, 2, 3, 4, 5, 6, 7, 8, 9);

$buildList = array(1, 3, 5, 'trunk', 0, '');

$bug = new bugTest();
r($bug->linkBugToBuildTest($bugIDList[0], $buildList[0])) && p('bugs', '-')  && e('1');     // 把bug 1关联到build 1
r($bug->linkBugToBuildTest($bugIDList[1], $buildList[1])) && p('bugs', '-')  && e('2');     // 把bug 2关联到build 3
r($bug->linkBugToBuildTest($bugIDList[2], $buildList[2])) && p('bugs', '-')  && e('3');     // 把bug 3关联到build 5
r($bug->linkBugToBuildTest($bugIDList[3], $buildList[0])) && p('bugs', '-')  && e('1,4');   // 把bug 4 关联到build 1
r($bug->linkBugToBuildTest($bugIDList[4], $buildList[0])) && p('bugs', '-')  && e('1,4,5'); // 把bug 5 关联到build 1
r($bug->linkBugToBuildTest($bugIDList[5], $buildList[3])) && p('bugs', '-')  && e('0');     // 把bug 6 关联到build trunk
r($bug->linkBugToBuildTest($bugIDList[6], $buildList[4])) && p('bugs', '-')  && e('0');     // 把bug 7 关联到build 0
r($bug->linkBugToBuildTest($bugIDList[7], $buildList[5])) && p('bugs', '-')  && e('0');     // 把bug 8 关联到build ''
