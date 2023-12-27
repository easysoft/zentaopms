#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(10);

/**

title=测试 commonTao->queryListForPreAndNext();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('common');

$_SESSION['storyQueryCondition'] = 'id < 5';
$_SESSION['storyOnlyCondition']  = true;
$sql  = $tester->common->getPreAndNextSQL('story');
$list = $tester->common->queryListForPreAndNext('story', $sql);
r(implode('|', $list['objectList'])) && p() && e('1|2|3|4');

$_SESSION['storyQueryCondition'] = 'id <= 5';
$sql  = $tester->common->getPreAndNextSQL('story');
$list = $tester->common->queryListForPreAndNext('story', $sql);
r(implode('|', $list['objectList'])) && p() && e('1|2|3|4|5');

$tester->common->app->tab = 'my';
$tester->common->app->moduleName = 'product';
$tester->common->app->methodName = 'browse';
$_SESSION['app-my']['storyBrowseList'] = array('sql' => 'SELECT * FROM `zt_story` WHERE id <= 5', 'idkey' => 'id', 'objectList' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4));
$list = $tester->common->queryListForPreAndNext('story', $sql);
r(implode('|', $list['objectList'])) && p() && e('1|2|3|4');
