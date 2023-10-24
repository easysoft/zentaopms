#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->finish();
cid=1
pid=1

wait状态任务完成 >> status,wait,done
doing状态任务完成 >> status,doing,done
done状态任务完成 >> finishedBy,,admin
pause状态任务完成 >> status,pause,done
cancel状态任务完成 >> status,cancel,done
closed状态任务完成 >> status,closed,done
总计消耗异常验证 >> "总计消耗"必须大于之前消耗
本次消耗不为0 >> "本次消耗"不能为0

*/

$taskIDList = array('7','8','9','10','11','12','13','14');

$currencyTask        = array('consumed' => '17', 'currentConsumed' => '1');
$consumedTask        = array('consumed' => '0', 'currentConsumed' => '1');
$currentConsumedTask = array('consumed' => '10', 'currentConsumed' => '0');

$task = new taskTest();
sleep(2);
//var_dump($task->finishTest($taskIDList[2],$currencyTask));die;
r($task->finishTest($taskIDList[0],$currencyTask))        && p('0:field,old,new') && e('status,wait,done');   //wait状态任务完成
r($task->finishTest($taskIDList[1],$currencyTask))        && p('0:field,old,new') && e('status,doing,done');  //doing状态任务完成
r($task->finishTest($taskIDList[2],$currencyTask))        && p('4:field,old,new') && e('finishedBy,,admin');  //done状态任务完成
r($task->finishTest($taskIDList[3],$currencyTask))        && p('0:field,old,new') && e('status,pause,done');  //pause状态任务完成
r($task->finishTest($taskIDList[4],$currencyTask))        && p('0:field,old,new') && e('status,cancel,done'); //cancel状态任务完成
r($task->finishTest($taskIDList[5],$currencyTask))        && p('0:field,old,new') && e('status,closed,done'); //closed状态任务完成
r($task->finishTest($taskIDList[6],$consumedTask))        && p() && e('"总计消耗"必须大于之前消耗');          //总计消耗异常验证
r($task->finishTest($taskIDList[7],$currentConsumedTask)) && p() && e('"本次消耗"不能为0');                   //本次消耗不为0
