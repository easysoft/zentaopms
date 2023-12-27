#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(10);

/**

title=测试 commonModel->getPreAndNextObject();
timeout=0
cid=1

- 执行common模块的getPreAndNextObject方法，参数是'', 3
 - 属性pre @~~
 - 属性next @~~
- 执行common模块的getPreAndNextObject方法，参数是'story', 3
 - 属性pre @~~
 - 属性next @~~
- 执行$preAndNextObject->pre属性id @2
- 执行$preAndNextObject->next属性id @4
- 执行$preAndNextObject->pre属性id @2
- 执行$preAndNextObject->next属性id @4

*/

global $tester;
$tester->loadModel('common');

$_SESSION['QueryCondition'] = '';
r(1) && p() && e('1');
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
