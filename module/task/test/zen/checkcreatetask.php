#!/usr/bin/env php
<?php

/**

title=测试 taskZen::checkCreateTask();
timeout=0
cid=18921

- 执行taskTest模块的checkCreateTaskTest方法  @1
- 执行taskTest模块的checkCreateTaskTest方法 属性estimate @预计不能为负数
- 执行taskTest模块的checkCreateTaskTest方法 属性assignedTo @多人任务团队不能为空。
- 执行taskTest模块的checkCreateTaskTest方法 属性deadline @"截止日期"必须大于"预计开始"
- 执行taskTest模块的checkCreateTaskTest方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

zendata('task')->loadYaml('task_checkcreatetask', false, 2)->gen(10);
zendata('project')->loadYaml('project_checkcreatetask', false, 2)->gen(1);

su('admin');

$taskTest = new taskZenTest();

r($taskTest->checkCreateTaskTest((object)array('estimate' => 10, 'project' => 1, 'execution' => 1, 'parent' => 0, 'estStarted' => '2024-01-01', 'deadline' => '2024-01-10'), array())) && p() && e('1');
r($taskTest->checkCreateTaskTest((object)array('estimate' => -5, 'project' => 1, 'execution' => 1, 'parent' => 0, 'estStarted' => '2024-01-01', 'deadline' => '2024-01-10'), array())) && p('estimate') && e('预计不能为负数');
r($taskTest->checkCreateTaskTest((object)array('estimate' => 10, 'project' => 1, 'execution' => 1, 'parent' => 0, 'estStarted' => '2024-01-01', 'deadline' => '2024-01-10', 'multiple' => true), array())) && p('assignedTo') && e('多人任务团队不能为空。');
r($taskTest->checkCreateTaskTest((object)array('estimate' => 10, 'project' => 1, 'execution' => 1, 'parent' => 0, 'estStarted' => '2024-01-10', 'deadline' => '2024-01-05'), array())) && p('deadline') && e('"截止日期"必须大于"预计开始"');
r($taskTest->checkCreateTaskTest((object)array('estimate' => 0, 'project' => 1, 'execution' => 1, 'parent' => 0, 'estStarted' => '0000-00-00', 'deadline' => '0000-00-00'), array())) && p() && e('1');