#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(10);

/**

title=测试 commonModel->getPreAndNextObject();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('common');

r((array)$tester->common->getPreAndNextObject('', 3))      && p('pre,next') && e('~~,~~');
r((array)$tester->common->getPreAndNextObject('story', 3)) && p('pre,next') && e('~~,~~');

$tester->common->app->moduleName = 'product';
$tester->common->app->methodName = 'browse';
$_SESSION['storyQueryCondition'] = 'id < 5';
$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyOrderBy']        = 'id';

$preAndNextObject = $tester->common->getPreAndNextObject('story', 3);
r($preAndNextObject->pre)  && p('id') && e('2');
r($preAndNextObject->next) && p('id') && e('4');

$_SESSION['storyQueryCondition'] = 'SELECT * FROM `zt_story` WHERE id <= 4';
$_SESSION['storyOnlyCondition']  = false;
$preAndNextObject = $tester->common->getPreAndNextObject('story', 3);
r($preAndNextObject->pre)  && p('id') && e('2');
r($preAndNextObject->next) && p('id') && e('4');
