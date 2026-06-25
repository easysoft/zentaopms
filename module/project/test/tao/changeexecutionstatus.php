#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zenData('project')->loadYaml('execution')->gen(2);

/**

title=测试 projectTao::changeExecutionStatus();
timeout=0
cid=17890

- 测试修改无迭代项目下执行的状态为suspend属性status @suspended
- 测试修改无迭代项目下执行的状态为start属性status @doing
- 测试修改无迭代项目下执行的状态为wait属性status @doing
- 测试修改无迭代项目下执行的状态为closed属性status @closed
- 测试修改无迭代项目下执行的状态为closed属性status @closed

*/

$_POST['realBegan'] = '2023-01-01';
$_POST['begin']     = '2023-01-01';
$_POST['end']       = '2024-01-01';
$_POST['realEnd']   = '2023-08-01';
$_POST['uid']       = '0';

global $tester;

$projectModel = $tester->loadModel('project');
$actionList   = array('suspend', 'start', 'activate', 'close', 'none');
$resultList   = array();
foreach($actionList as $action)
{
    $project = new stdclass();
    $project->lastEditedBy   = 'admin';
    $project->lastEditedDate = helper::now();
    $project->status         = 'doing';
    if($action == 'start') $project->realBegan = helper::today();
    if($action == 'suspend')
    {
        $project->status = 'suspended';
        $project->suspendedDate = helper::now();
    }
    if($action == 'close')
    {
        $project->status     = 'closed';
        $project->realEnd    = helper::today();
        $project->closedBy   = 'admin';
        $project->closedDate = helper::now();
    }
    $projectModel->dao->update(TABLE_PROJECT)->data($project)->where('id')->eq(1)->exec();
    $projectModel->changeExecutionStatus(1, $action);
    $resultList[$action] = $projectModel->dao->select('id,status')->from(TABLE_PROJECT)->where('id')->eq(2)->fetch();

}

r($resultList['suspend'])  && p('status') && e("suspended"); // 测试修改无迭代项目下执行的状态为suspend
r($resultList['start'])    && p('status') && e("doing");     // 测试修改无迭代项目下执行的状态为start
r($resultList['activate']) && p('status') && e("doing");     // 测试修改无迭代项目下执行的状态为wait
r($resultList['close'])    && p('status') && e("closed");    // 测试修改无迭代项目下执行的状态为closed
r($resultList['none'])     && p('status') && e("closed");    // 测试修改无迭代项目下执行的状态为closed
