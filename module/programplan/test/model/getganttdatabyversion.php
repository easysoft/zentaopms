#!/usr/bin/env php
<?php
/**

title=测试 projectModel->getGanttDataByVersion();
timeout=0
cid=1

- 执行$deliverableVersionData['data'] @1
- 执行$baselineVersionData['link'] @1
- 执行$deliverableVersionData) == $jsonedGanttData @1
- 执行$baselineVersionData)    == $jsonedGanttData @1
- 执行$ganttVersionData)       == $jsonedGanttData @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('docblock')->gen(1);
$object = zenData('object');
$object->project->range('1');
$object->type->range('reviewed,taged,taged');
$object->category->range('1,1,0');
$object->status->range('pass,pass,gantt');
$object->gen(3);

$review = zenData('review');
$review->object->range('1-10');
$review->status->range('pass');
$review->type->range('deliverable,baseline');
$review->gen(2);

$ganttData = array();
$ganttData['data'] = array('1' => array('id' => 1, 'type' => 'task', 'name' => 'test'));
$ganttData['link'] = array();

su('admin');

global $tester;

$projectModel = $tester->loadModel('project');
$projectModel->dao->update(TABLE_DOCBLOCK)->set('content')->eq(json_encode(array('ganttOptions' => $ganttData)))->where('id')->eq(1)->exec();
$projectModel->dao->update(TABLE_OBJECT)->set('data')->eq(json_encode(array('fetcherParams' => array('param2' => '1'))))->where('id')->eq('1')->exec();
$projectModel->dao->update(TABLE_OBJECT)->set('categoryVersion')->eq(json_encode(array('1' => 1)))->set('data')->eq('')->where('id')->eq('2')->exec();
$projectModel->dao->update(TABLE_OBJECT)->set('data')->eq(json_encode($ganttData))->where('id')->eq(3)->exec();

$deliverableVersionData = $projectModel->getGanttDataByVersion(1);
$baselineVersionData    = $projectModel->getGanttDataByVersion(2);
$ganttVersionData       = $projectModel->getGanttDataByVersion(3);
$jsonedGanttData        = json_encode($ganttData);

r(isset($deliverableVersionData['data']))             && p() && e('1');
r(isset($baselineVersionData['link']))                && p() && e('1');
r(json_encode($deliverableVersionData) == $jsonedGanttData) && p() && e('1');
r(json_encode($baselineVersionData)    == $jsonedGanttData) && p() && e('1');
r(json_encode($ganttVersionData)       == $jsonedGanttData) && p() && e('1');