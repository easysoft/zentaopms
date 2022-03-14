#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->fixFirstTest();
cid=1
pid=1

敏捷执行关联用例 >> 101,1,1
瀑布执行关联用例 >> 131,43,169
看板执行关联用例 >> 161,68,269
敏捷执行关联用例统计 >> 4
瀑布执行关联用例统计 >> 4
看板执行关联用例统计 >> 4

*/

$executionIDList = array('101', '131');

$scrumEstimate     = array('estimate' => '25');
$withLeft          = array('estimate' => '21', 'withLeft' => '1');
$waterfallEstimate = array('estimate' => '17', 'withLeft' => '1');

$execution = new executionTest();
r($execution->fixFirstTest($executionIDList[0], $scrumEstimate))     && p('0:estimate') && e('26'); // 敏捷执行更新首日工时
r($execution->fixFirstTest($executionIDList[0], $withLeft))          && p('0:estimate') && e('26'); // 敏捷执行更新首日剩余工时
r($execution->fixFirstTest($executionIDList[1], $waterfallEstimate)) && p('0:left')     && e('17'); // 瀑布执行更新首日剩余工时
