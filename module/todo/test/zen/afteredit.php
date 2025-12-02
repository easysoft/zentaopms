#!/usr/bin/env php
<?php

/**

title=测试 todoZen::afterEdit();
timeout=0
cid=19287

- 执行todoTest模块的afterEditTest方法，参数是1, array  @no_changes
- 执行todoTest模块的afterEditTest方法，参数是1, array
 - 属性processed @1
 - 属性changesCount @2
 - 属性actionCreated @1
 - 属性historyLogged @1
- 执行todoTest模块的afterEditTest方法，参数是999, array
 - 属性processed @1
 - 属性changesCount @1
 - 属性actionCreated @1
- 执行todoTest模块的afterEditTest方法，参数是2, array
 - 属性processed @1
 - 属性changesCount @3
- 执行todoTest模块的afterEditTest方法，参数是0, array
 - 属性processed @1
 - 属性actionCreated @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

zenData('todo')->loadYaml('todo_afteredit', false, 2)->gen(10);
zenData('action')->loadYaml('action_afteredit', false, 2)->gen(5);
zenData('history')->loadYaml('history_afteredit', false, 2)->gen(5);

su('admin');

$todoTest = new todoTest();

r($todoTest->afterEditTest(1, array())) && p() && e('no_changes');
r($todoTest->afterEditTest(1, array('name' => array('旧名称', '新名称'), 'status' => array('wait', 'doing')))) && p('processed,changesCount,actionCreated,historyLogged') && e('1,2,1,1');
r($todoTest->afterEditTest(999, array('name' => array('旧名称', '新名称')))) && p('processed,changesCount,actionCreated') && e('1,1,1');
r($todoTest->afterEditTest(2, array('name' => array('task1', 'task2'), 'pri' => array('2', '1'), 'desc' => array('旧描述', '新描述')))) && p('processed,changesCount') && e('1,3');
r($todoTest->afterEditTest(0, array('status' => array('wait', 'done')))) && p('processed,actionCreated') && e('1,0');