#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zdTable('project')->config('execution')->gen(2);

/**

title=测试 projectTao::changeExecutionStatus();
timeout=0
cid=1

*/

$_POST['realBegan'] = '2023-01-01';
$_POST['begin']     = '2023-01-01';
$_POST['end']       = '2024-01-01';
$_POST['realEnd']   = '2023-08-01';
$_POST['uid']       = '0';

global $tester;

$statusList = array('suspend', 'start', 'activate', 'close');
foreach($statusList as $status)
{
    $tester->loadModel('project')->changeExecutionStatus(1, $status);
    ${$status} = $tester->project->getByID(2);
}

r($suspend)  && p('status') && e("suspend");
r($start)    && p('status') && e("start");
r($activate) && p('status') && e("wait");
r($close)    && p('status') && e("close");
