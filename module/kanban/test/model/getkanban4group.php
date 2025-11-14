#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

zenData('user')->gen(10);
zenData('taskteam')->gen(10);
zenData('module')->gen(10);
zenData('story')->gen(20);
zenData('projectstory')->gen(20);
zenData('product')->gen(20);
zenData('storyreview')->gen(20);
zenData('bug')->gen(20);
zenData('project')->loadYaml('kanbanexecution')->gen(5);
zenData('kanbanregion')->loadYaml('rdkanbanregion')->gen(5);
zenData('kanbangroup')->loadYaml('rdkanbangroup')->gen(20);
zenData('kanbancolumn')->gen(20);
zenData('kanbanlane')->loadYaml('rdkanbanlane')->gen(10);
zenData('kanbancell')->loadYaml('rdkanbancell')->gen(20);
zenData('task')->loadYaml('rdkanbantask')->gen(20);

su('admin');

/**

title=测试 kanbanModel->getKanban4Group();
timeout=0
cid=16921

- 查看执行1按需求的优先级分组的泳道
 - 第0条的id属性 @pri0
 - 第0条的title属性 @优先级: 无
- 查看执行1按需求的分类分组的泳道
 - 第0条的id属性 @categoryfeature
 - 第0条的title属性 @功能
- 查看执行1按需求的模块分组的泳道
 - 第0条的id属性 @module0
 - 第0条的title属性 @所属模块: 无
- 查看执行1按需求的来源分组的泳道
 - 第0条的id属性 @source0
 - 第0条的title属性 @来源: 无
- 查看执行2按任务的优先级分组的泳道
 - 第0条的id属性 @pri1
 - 第0条的title属性 @优先级:1
- 查看执行2按任务的模块分组的泳道
 - 第0条的id属性 @module0
 - 第0条的title属性 @所属模块: 无
- 查看执行2按任务的指派给分组的泳道
 - 第0条的id属性 @assignedTo0
 - 第0条的title属性 @ 指派给: 无
- 查看执行2按任务的相关需求分组的泳道第0条的id属性 @story0
- 查看执行3按bug的优先级分组的泳道
 - 第0条的id属性 @pri0
 - 第0条的title属性 @优先级: 无
- 查看执行3按bug的模块分组的泳道
 - 第0条的id属性 @module0
 - 第0条的title属性 @所属模块: 无
- 查看执行3按bug的指派给分组的泳道
 - 第0条的id属性 @assignedTo0
 - 第0条的title属性 @指派给: 无
- 查看执行3按bug的严重程度分组的泳道
 - 第0条的id属性 @severity0
 - 第0条的title属性 @严重程度: 无
- 查看执行4按需求的优先级分组的泳道
 - 第0条的id属性 @pri0
 - 第0条的title属性 @优先级: 无
- 查看执行5按需求的优先级分组的泳道
 - 第0条的id属性 @pri0
 - 第0条的title属性 @优先级: 无

*/

$executionIDList = array('1', '2', '3', '4', '5');
$browseTypeList  = array('story', 'task', 'bug');
$groupByList     = array('pri', 'category', 'module', 'source', 'assignedTo', 'story', 'severity');

$kanban = new kanbanTest();

r($kanban->getKanban4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[0])[0]['data']['lanes']) && p('0:id,title') && e('pri0,优先级: 无');         // 查看执行1按需求的优先级分组的泳道
r($kanban->getKanban4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[1])[0]['data']['lanes']) && p('0:id,title') && e('categoryfeature,功能');    // 查看执行1按需求的分类分组的泳道
r($kanban->getKanban4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[2])[0]['data']['lanes']) && p('0:id,title') && e('module0,所属模块: 无');    // 查看执行1按需求的模块分组的泳道
r($kanban->getKanban4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[3])[0]['data']['lanes']) && p('0:id,title') && e('source0,来源: 无');        // 查看执行1按需求的来源分组的泳道
r($kanban->getKanban4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[0])[0]['data']['lanes']) && p('0:id,title') && e('pri1,优先级:1');           // 查看执行2按任务的优先级分组的泳道
r($kanban->getKanban4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[2])[0]['data']['lanes']) && p('0:id,title') && e('module0,所属模块: 无');    // 查看执行2按任务的模块分组的泳道
r($kanban->getKanban4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[4])[0]['data']['lanes']) && p('0:id,title') && e('assignedTo0, 指派给: 无'); // 查看执行2按任务的指派给分组的泳道
r($kanban->getKanban4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[5])[0]['data']['lanes']) && p('0:id')       && e('story0');                  // 查看执行2按任务的相关需求分组的泳道
r($kanban->getKanban4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[0])[0]['data']['lanes']) && p('0:id,title') && e('pri0,优先级: 无');         // 查看执行3按bug的优先级分组的泳道
r($kanban->getKanban4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[2])[0]['data']['lanes']) && p('0:id,title') && e('module0,所属模块: 无');    // 查看执行3按bug的模块分组的泳道
r($kanban->getKanban4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[4])[0]['data']['lanes']) && p('0:id,title') && e('assignedTo0,指派给: 无');  // 查看执行3按bug的指派给分组的泳道
r($kanban->getKanban4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[6])[0]['data']['lanes']) && p('0:id,title') && e('severity0,严重程度: 无');  // 查看执行3按bug的严重程度分组的泳道
r($kanban->getKanban4GroupTest($executionIDList[3], $browseTypeList[0], $groupByList[0])[0]['data']['lanes']) && p('0:id,title') && e('pri0,优先级: 无');         // 查看执行4按需求的优先级分组的泳道
r($kanban->getKanban4GroupTest($executionIDList[4], $browseTypeList[0], $groupByList[0])[0]['data']['lanes']) && p('0:id,title') && e('pri0,优先级: 无');         // 查看执行5按需求的优先级分组的泳道