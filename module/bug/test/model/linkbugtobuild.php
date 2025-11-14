#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('bug')->gen(10);
zenData('user')->gen(1);
zenData('build')->gen(10);
zenData('release')->loadYaml('release')->gen(10);

su('admin');

/**

title=测试bugModel->linkBugToBuild();
cid=15402

- 把bug 1关联到build 1属性bugs @1

- 把bug 2关联到build 3属性bugs @2

- 把bug 3关联到build 5属性bugs @3

- 把bug 4 关联到build 1属性bugs @1,4

- 把bug 5 关联到build 1属性bugs @1,4,5

- 把bug 6 关联到build trunk属性bugs @0

- 把bug 7 关联到build 0属性bugs @0

- 把bug 8 关联到build ''属性bugs @0

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
