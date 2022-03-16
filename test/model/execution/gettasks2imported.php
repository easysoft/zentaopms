#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getTasks2ImportedTest();
cid=1
pid=1

敏捷执行任务查看 >> 更多任务1
瀑布执行任务查看 >> test
看板执行任务查看 >> wait
敏捷执行任务统计 >> 3
敏捷执行任务统计 >> 3
敏捷执行任务统计 >> 3

*/

$executionIDList = array('101', '131', '161');
$count         = array('0','1');

$execution = new executionTest();
r($execution->getTasks2ImportedTest($executionIDList[0],$count[0])) && p('601:name')  && e('更多任务1'); // 敏捷执行任务查看
r($execution->getTasks2ImportedTest($executionIDList[1],$count[0])) && p('691:type')  && e('test');      // 瀑布执行任务查看
r($execution->getTasks2ImportedTest($executionIDList[2],$count[0])) && p('61:status') && e('wait');      // 看板执行任务查看
r($execution->getTasks2ImportedTest($executionIDList[0],$count[1])) && p()            && e('3');         // 敏捷执行任务统计
r($execution->getTasks2ImportedTest($executionIDList[1],$count[1])) && p()            && e('3');         // 敏捷执行任务统计
r($execution->getTasks2ImportedTest($executionIDList[2],$count[1])) && p()            && e('3');         // 敏捷执行任务统计