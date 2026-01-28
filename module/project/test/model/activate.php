#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');
$projectTable = zenData('project')->loadYaml('project');
$projectTable->status->range('closed');
$projectTable->gen(20);

/**

title=测试 projectModel->activate();
timeout=0
cid=17796

- 测试激活瀑布项目
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @doing
- 测试激活敏捷项目
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @doing
- 测试激活看板项目
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @doing
- 测试激活项目型敏捷项目
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @doing
- 测试激活项目型看板项目
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @doing
- 测试激活项目型瀑布项目
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @doing
- 测试激活无迭代的看板项目
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @doing
- 测试激活无迭代的瀑布项目
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @doing
- 测试激活无迭代的敏捷项目
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @doing
- 测试激活无产品无迭代的瀑布项目
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @doing
- 测试激活无产品无迭代的敏捷项目
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @doing
- 测试激活无产品无迭代的看板项目
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @doing

*/

global $tester;
$tester->loadModel('project');

$data = new stdClass();
$data->status       = 'doing';
$data->begin        = '2022-10-10';
$data->end          = '2022-10-10';
$data->status       = 'doing';
$data->comment      = '这是一条备注';
$data->readjustTime = 1;
$data->readjustTask = 1;

$normalIdList                = array(2, 4, 6);
$noProductIdList             = array(1, 3, 5);
$noExecutionIdList           = array(12, 14, 16);
$noExecutionAndProductIdList = array(11, 13, 15);

r($tester->project->activate($normalIdList[0],                $data)) && p('0:field,old,new') && e('status,closed,doing'); // 测试激活瀑布项目
r($tester->project->activate($normalIdList[1],                $data)) && p('0:field,old,new') && e('status,closed,doing'); // 测试激活敏捷项目
r($tester->project->activate($normalIdList[2],                $data)) && p('0:field,old,new') && e('status,closed,doing'); // 测试激活看板项目
r($tester->project->activate($noProductIdList[0],             $data)) && p('0:field,old,new') && e('status,closed,doing'); // 测试激活项目型敏捷项目
r($tester->project->activate($noProductIdList[1],             $data)) && p('0:field,old,new') && e('status,closed,doing'); // 测试激活项目型看板项目
r($tester->project->activate($noProductIdList[2],             $data)) && p('0:field,old,new') && e('status,closed,doing'); // 测试激活项目型瀑布项目
r($tester->project->activate($noExecutionIdList[0],           $data)) && p('0:field,old,new') && e('status,closed,doing'); // 测试激活无迭代的看板项目
r($tester->project->activate($noExecutionIdList[1],           $data)) && p('0:field,old,new') && e('status,closed,doing'); // 测试激活无迭代的瀑布项目
r($tester->project->activate($noExecutionIdList[2],           $data)) && p('0:field,old,new') && e('status,closed,doing'); // 测试激活无迭代的敏捷项目
r($tester->project->activate($noExecutionAndProductIdList[0], $data)) && p('0:field,old,new') && e('status,closed,doing'); // 测试激活无产品无迭代的瀑布项目
r($tester->project->activate($noExecutionAndProductIdList[1], $data)) && p('0:field,old,new') && e('status,closed,doing'); // 测试激活无产品无迭代的敏捷项目
r($tester->project->activate($noExecutionAndProductIdList[2], $data)) && p('0:field,old,new') && e('status,closed,doing'); // 测试激活无产品无迭代的看板项目