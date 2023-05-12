#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zdTable('project')->config('project')->gen(5);

/**

title=测试 projectTao::getOngoingExecutions();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');
$executions = $tester->project->getOngoingExecutions();

r($executions[5]) && p('name,code') && e('项目5,project5'); // 获取下标为5的进行中的执行列表的name和code字段
