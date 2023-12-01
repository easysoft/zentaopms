#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/my.class.php';

zdTable('project')->config('program')->gen('15');
zdTable('product')->config('product')->gen('20');
zdTable('team')->gen('20');
zdTable('bug')->gen('20');
zdTable('task')->config('task')->gen('20');
zdTable('story')->config('story')->gen('20');
zdTable('doc')->gen('20');
zdTable('user')->gen('1');

su('admin');

/**

title=测试 myModel->getContribute();
cid=1
pid=1

myTaskTotal数据获取 >> 0
myStoryTotal数据获取 >> 0
myBugTotal数据获取 >> 85
docCreatedTotal数据获取 >> 900
ownerProductTotal数据获取 >> 0
involvedProjectTotal数据获取 >> 1

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
