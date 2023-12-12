#!/usr/bin/env php
<?php
/**
title=测试 pivotModel->geProjectExecution();
cid=1
pid=1

获取项目下的执行，如果是项目下的执行，格式则为「项目名称」/ [执行名称]  >> /项目集1,/项目集5/项目集10
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';
su('admin');

zdTable('project')->gen(0);
zdTable('project')->config('project')->gen(10);
zdTable('project')->config('execution_project')->gen(10, false, false);

$pivot = new pivotTest();

r($pivot->getProjectExecutions()) && p('101,110') && e('/项目集1,项目集5/项目集10');   //获取项目下的执行，如果是项目下的执行，格式则为「项目名称」/ [执行名称]
