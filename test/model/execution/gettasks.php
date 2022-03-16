#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getTasksTest();
cid=1
pid=1

敏捷执行任务查询 >> 101,开发任务11
瀑布执行任务查询 >> 131,41
看板执行任务查询 >> 161,done
正常产品查询任务 >> 101,开发任务11
unclosed任务查询 >> 101,wait
wait任务查询 >> 101,wait
doing任务查询 >> 101,doing
undone任务查询 >> 101,doing
done任务查询 >> 101,done
根据查询条件查询任务 >> 101,开发任务11
根据模块查询任务 >> 101,21
name_asc,id_asc排序查询 >> 101,开发任务11
id_asc排序查询 >> 101,design
pri_desc,id_desc排序查询 >> 101,wait
敏捷执行任务查询统计 >> 4
瀑布执行任务查询统计 >> 4
看板执行任务查询统计 >> 4
正常产品查询任务统计 >> 1
unclosed任务查询统计 >> 4
wat任务查询统计 >> 2
doing任务查询统计 >> 1
undone任务查询统计 >> 3
done任务查询统计 >> 1
根据查询条件查询任务统计 >> 1
根据模块查询任务统计 >> 2
name_asc,id_asc排序查询统计 >> 4
id_asc排序查询统计 >> 4
pri_desc,id_desc排序查询统计 >> 4

*/

$executionIDList = array('0', '101', '131', '161');
$productIDList   = array('0', '1');
$browseType      = array('all', 'unclosed', 'wait', 'doing', 'undone', 'done', 'closed', 'bysearch');
$queryID         = array('0', '1');
$moduleID        = array('0', '21', '22');
$sort            = array('status,id_desc', 'name_asc,id_asc', 'id_asc', 'pri_desc,id_desc');
$count           = array('0', '1');

$execution = new executionTest();
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[0],$queryID[0],$moduleID[0],$sort[0],$count[0])) && p('1:execution,name')      && e('101,开发任务11'); // 敏捷执行任务查询
r($execution->getTasksTest($productIDList[0],$executionIDList[2],$browseType[0],$queryID[0],$moduleID[0],$sort[0],$count[0])) && p('693:execution,project') && e('131,41');         // 瀑布执行任务查询
r($execution->getTasksTest($productIDList[0],$executionIDList[3],$browseType[0],$queryID[0],$moduleID[0],$sort[0],$count[0])) && p('783:execution,status')  && e('161,done');       // 看板执行任务查询
r($execution->getTasksTest($productIDList[1],$executionIDList[1],$browseType[0],$queryID[0],$moduleID[0],$sort[0],$count[0])) && p('1:execution,name')      && e('101,开发任务11'); // 正常产品查询任务
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[1],$queryID[0],$moduleID[0],$sort[0],$count[0])) && p('1:execution,status')    && e('101,wait');       // unclosed任务查询
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[2],$queryID[0],$moduleID[0],$sort[0],$count[0])) && p('601:execution,status')  && e('101,wait');       // wait任务查询
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[3],$queryID[0],$moduleID[0],$sort[0],$count[0])) && p('602:execution,status')  && e('101,doing');      // doing任务查询
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[4],$queryID[0],$moduleID[0],$sort[0],$count[0])) && p('602:execution,status')  && e('101,doing');      // undone任务查询
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[5],$queryID[0],$moduleID[0],$sort[0],$count[0])) && p('603:execution,status')  && e('101,done');       // done任务查询
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[7],$queryID[1],$moduleID[0],$sort[0],$count[0])) && p('1:execution,name')      && e('101,开发任务11'); // 根据查询条件查询任务
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[0],$queryID[0],$moduleID[1],$sort[0],$count[0])) && p('601:execution,module')  && e('101,21');         // 根据模块查询任务
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[0],$queryID[0],$moduleID[0],$sort[1],$count[0])) && p('1:execution,name')      && e('101,开发任务11'); // name_asc,id_asc排序查询
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[0],$queryID[0],$moduleID[0],$sort[2],$count[0])) && p('601:execution,type')    && e('101,design');     // id_asc排序查询
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[0],$queryID[0],$moduleID[0],$sort[3],$count[0])) && p('1:execution,status')    && e('101,wait');       // pri_desc,id_desc排序查询
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[0],$queryID[0],$moduleID[0],$sort[0],$count[1])) && p()                        && e('4');              // 敏捷执行任务查询统计
r($execution->getTasksTest($productIDList[0],$executionIDList[2],$browseType[0],$queryID[0],$moduleID[0],$sort[0],$count[1])) && p()                        && e('4');              // 瀑布执行任务查询统计
r($execution->getTasksTest($productIDList[0],$executionIDList[3],$browseType[0],$queryID[0],$moduleID[0],$sort[0],$count[1])) && p()                        && e('4');              // 看板执行任务查询统计
r($execution->getTasksTest($productIDList[1],$executionIDList[1],$browseType[0],$queryID[0],$moduleID[0],$sort[0],$count[1])) && p()                        && e('1');              // 正常产品查询任务统计
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[1],$queryID[0],$moduleID[0],$sort[0],$count[1])) && p()                        && e('4');              // unclosed任务查询统计
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[2],$queryID[0],$moduleID[0],$sort[0],$count[1])) && p()                        && e('2');              // wat任务查询统计
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[3],$queryID[0],$moduleID[0],$sort[0],$count[1])) && p()                        && e('1');              // doing任务查询统计
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[4],$queryID[0],$moduleID[0],$sort[0],$count[1])) && p()                        && e('3');              // undone任务查询统计
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[5],$queryID[0],$moduleID[0],$sort[0],$count[1])) && p()                        && e('1');              // done任务查询统计
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[7],$queryID[1],$moduleID[0],$sort[0],$count[1])) && p()                        && e('1');              // 根据查询条件查询任务统计
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[0],$queryID[0],$moduleID[1],$sort[0],$count[1])) && p()                        && e('2');              // 根据模块查询任务统计
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[0],$queryID[0],$moduleID[0],$sort[1],$count[1])) && p()                        && e('4');              // name_asc,id_asc排序查询统计
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[0],$queryID[0],$moduleID[0],$sort[2],$count[1])) && p()                        && e('4');              // id_asc排序查询统计
r($execution->getTasksTest($productIDList[0],$executionIDList[1],$browseType[0],$queryID[0],$moduleID[0],$sort[3],$count[1])) && p()                        && e('4');              // pri_desc,id_desc排序查询统计