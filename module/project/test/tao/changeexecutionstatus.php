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

global $tester;

$statusList = array('suspend', 'start', 'activate', 'close');
foreach($statusList as $status)
{
    $tester->loadModel('project')->changeExecutionStatus(1, $status);
    $$status = $tester->project->getByID(2);
}

r($suspend)  && p('status') && e("suspended");
r($start)    && p('status') && e("doing");
r($activate) && p('status') && e("wait");
r($close)    && p('status') && e("closed");
