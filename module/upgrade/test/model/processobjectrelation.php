#!/usr/bin/env php
<?php

/**

title=upgradeModel->processLinkStories();
timeout=0
cid=1

- 开源版不执行 @0
- 开源版不执行 @0
- 开源版不执行 @0
- 开源版不执行 @0
- 开源版不执行 @0
- 开源版不执行 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

su('admin');

$bug = zenData('bug');
$bug->task->range('1');
$bug->story->range('2');
$bug->case->range('3');
$bug->relatedBug->range('4');
$bug->gen(10);

zenData('relation')->gen(0);

$upgrade = new upgradeTest();
r($upgrade->processObjectRelationTest('story', 2)) && p('') && e('0'); // 开源版不执行
r($upgrade->processObjectRelationTest('case', 3))  && p('') && e('0'); // 开源版不执行
r($upgrade->processObjectRelationTest('bug', 4))   && p('') && e('0'); // 开源版不执行
r($upgrade->processObjectRelationTest('story', 2)) && p('') && e('0'); // 开源版不执行
r($upgrade->processObjectRelationTest('case', 3))  && p('') && e('0'); // 开源版不执行
r($upgrade->processObjectRelationTest('bug', 4))   && p('') && e('0'); // 开源版不执行
