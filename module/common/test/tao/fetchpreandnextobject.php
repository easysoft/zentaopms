#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(10);

/**

title=测试 commonTao->fetchPreAndNextObject();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('common');

$_SESSION['storyQueryCondition'] = 'id < 40';
$_SESSION['storyOnlyCondition']  = true;

$preAndNextObject = new stdclass();
$preAndNextObject->pre  = '';
$preAndNextObject->next = '';
r(1) && p() && e('1');
r((array)$tester->common->fetchPreAndNextObject('', 3, $preAndNextObject))      && p('pre,next') && e('~~,~~');
r((array)$tester->common->fetchPreAndNextObject('story', 3, $preAndNextObject)) && p('pre,next') && e('~~,~~');

$preAndNextObject->pre  = 2;
$preAndNextObject->next = 4;
$preAndNextObject = $tester->common->fetchPreAndNextObject('story', 3, $preAndNextObject);
r($preAndNextObject->pre)  && p('id') && e('2');
r($preAndNextObject->next) && p('id') && e('4');

$tester->common->app->moduleName = 'product';
$tester->common->app->methodName = 'browse';
$_SESSION['storyOnlyCondition']  =  false;
$_SESSION['storyBrowseList']     = array('sql' => 'SELECT * FROM `zt_story` WHERE id <= 5', 'idkey' => 'id', 'objectList' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4));

$preAndNextObject->pre  = 2;
$preAndNextObject->next = 4;
$preAndNextObject = $tester->common->fetchPreAndNextObject('story', 3, $preAndNextObject);
r($preAndNextObject->pre)  && p('id') && e('2');
r($preAndNextObject->next) && p('id') && e('4');

$preAndNextObject->pre  = 0;
$preAndNextObject->next = 6;
$preAndNextObject = $tester->common->fetchPreAndNextObject('story', 3, $preAndNextObject);
r($preAndNextObject->pre)  && p('id') && e('2');
r($preAndNextObject->next) && p('id') && e('4');
