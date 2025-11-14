#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('project')->loadYaml('program')->gen('15');
zenData('product')->loadYaml('product')->gen('20');
zenData('team')->gen('20');
zenData('bug')->gen('20');
zenData('task')->loadYaml('task')->gen('20');
zenData('story')->loadYaml('story')->gen('20');
zenData('doc')->gen('20');
zenData('user')->gen('1');

su('admin');

/**

title=测试 myModel->getContribute();
timeout=0
cid=17281

- myTaskTotal数据获取 @5
- myStoryTotal数据获取 @4
- myBugTotal数据获取 @20
- docCreatedTotal数据获取 @20
- ownerProductTotal数据获取 @10
- involvedProjectTotal数据获取 @1

*/

$my = new myTest();

$myTaskTotal          = $my->getContributeTest()->myTaskTotal;
$myStoryTotal         = $my->getContributeTest()->myStoryTotal;
$myBugTotal           = $my->getContributeTest()->myBugTotal;
$docCreatedTotal      = $my->getContributeTest()->docCreatedTotal;
$ownerProductTotal    = $my->getContributeTest()->ownerProductTotal;
$involvedProjectTotal = $my->getContributeTest()->involvedProjectTotal;

r($myTaskTotal)          && p() && e('5');  //myTaskTotal数据获取
r($myStoryTotal)         && p() && e('4');  //myStoryTotal数据获取
r($myBugTotal)           && p() && e('20'); //myBugTotal数据获取
r($docCreatedTotal)      && p() && e('20'); //docCreatedTotal数据获取
r($ownerProductTotal)    && p() && e('10'); //ownerProductTotal数据获取
r($involvedProjectTotal) && p() && e('1');  //involvedProjectTotal数据获取