#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->putoffTest();
cid=1
pid=1

wait执行延期 >> days,,5
敏捷执行延期 >> status,doing,wait
瀑布阶段延期 >> status,doing,wait
看板执行延期 >> status,doing,wait

*/

$executionIDList = array('101', '102', '132', '162');

$execution = new executionTest();
r($execution->putoffTest($executionIDList[0])) && p('0:field,old,new') && e('days,,5');           // wait执行延期
r($execution->putoffTest($executionIDList[1])) && p('0:field,old,new') && e('status,doing,wait'); // 敏捷执行延期
r($execution->putoffTest($executionIDList[2])) && p('0:field,old,new') && e('status,doing,wait'); // 瀑布阶段延期
r($execution->putoffTest($executionIDList[3])) && p('0:field,old,new') && e('status,doing,wait'); // 看板执行延期
$db->restoreDB();