#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zenData('project')->loadYaml('execution')->gen(2);

/**

title=测试 projectTao::changeExecutionStatus();
timeout=0
cid=17890

- 测试修改无迭代项目下执行的状态为suspend属性status @suspend
- 测试修改无迭代项目下执行的状态为start属性status @start
- 测试修改无迭代项目下执行的状态为wait属性status @wait
- 测试修改无迭代项目下执行的状态为close属性status @close
- 测试修改无迭代项目下执行的状态为none @~~

*/

$_POST['realBegan'] = '2023-01-01';
$_POST['begin']     = '2023-01-01';
$_POST['end']       = '2024-01-01';
$_POST['realEnd']   = '2023-08-01';
$_POST['uid']       = '0';

global $tester;

$statusList = array('suspend', 'start', 'activate', 'close', 'none');
foreach($statusList as $status)
{
    $tester->loadModel('project')->changeExecutionStatus(1, $status);
    ${$status} = $tester->project->getByID(2);
}

r($suspend)  && p('status') && e("suspend"); // 测试修改无迭代项目下执行的状态为suspend
r($start)    && p('status') && e("start");   // 测试修改无迭代项目下执行的状态为start
r($activate) && p('status') && e("wait");    // 测试修改无迭代项目下执行的状态为wait
r($close)    && p('status') && e("close");   // 测试修改无迭代项目下执行的状态为close
r($none)     && p()         && e("~~");      // 测试修改无迭代项目下执行的状态为none
