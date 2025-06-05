#!/usr/bin/env php
<?php

/**

title=测试 docModel->getFileSourcePairs();
cid=1

- 获取有附件的任务属性1 @开发任务11
- 获取有附件的Bug属性2 @BUG2
- 获取有附件的需求属性3 @用户需求3
- 获取有附件的用例属性4 @这个是测试用例4
- 获取有附件的任务数量 @2
- 获取有附件的Bug数量 @2
- 获取有附件的需求数量 @2
- 获取有附件的用例数量 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('task')->gen(10);
zenData('bug')->gen(10);
zenData('story')->gen(10);
zenData('case')->gen(10);
zenData('file')->gen(45);
zenData('user')->gen(5);
su('admin');

$docTester = new docTest();
$files     = $docTester->getFileSourcePairsTest();

r($files['task'])     && p('1') && e('开发任务11');      // 获取有附件的任务
r($files['bug'])      && p('2') && e('BUG2');            // 获取有附件的Bug
r($files['story'])    && p('3') && e('用户需求3');       // 获取有附件的需求
r($files['testcase']) && p('4') && e('这个是测试用例4'); // 获取有附件的用例

r(count($files['task']))     && p() && e('2'); // 获取有附件的任务数量
r(count($files['bug']))      && p() && e('2'); // 获取有附件的Bug数量
r(count($files['story']))    && p() && e('2'); // 获取有附件的需求数量
r(count($files['testcase'])) && p() && e('2'); // 获取有附件的用例数量
