#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->fixFirstTest();
cid=1
pid=1

不传入withLeft >> 26
敏捷执行更新首日剩余工时 >> 26
瀑布执行更新首日剩余工时 >> 17

*/

$executionIDList = array('101', '131');

$scrumEstimate     = array('estimate' => '25');
$withLeft          = array('estimate' => '21', 'withLeft' => '1');
$waterfallEstimate = array('estimate' => '17', 'withLeft' => '1');

$execution = new executionTest();
r($execution->fixFirstTest($executionIDList[0], $scrumEstimate))     && p('0:estimate') && e('26'); // 不传入withLeft
r($execution->fixFirstTest($executionIDList[0], $withLeft))          && p('0:estimate') && e('26'); // 敏捷执行更新首日剩余工时
r($execution->fixFirstTest($executionIDList[1], $waterfallEstimate)) && p('0:left')     && e('17'); // 瀑布执行更新首日剩余工时