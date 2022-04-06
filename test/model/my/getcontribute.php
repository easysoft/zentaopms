#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/my.class.php';
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

r($myTaskTotal)          && p() && e('0');  //myTaskTotal数据获取
r($myStoryTotal)         && p() && e('0');  //myStoryTotal数据获取
r($myBugTotal)           && p() && e('85'); //myBugTotal数据获取
r($docCreatedTotal)      && p() && e('900');//docCreatedTotal数据获取
r($ownerProductTotal)    && p() && e('0');  //ownerProductTotal数据获取
r($involvedProjectTotal) && p() && e('1');  //involvedProjectTotal数据获取