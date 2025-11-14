#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processWorkflowHooks();
timeout=0
cid=15872

- 执行convertTest模块的processWorkflowHooksTest方法，参数是array  @0
- 执行convertTest模块的processWorkflowHooksTest方法，参数是array  @0
- 执行convertTest模块的processWorkflowHooksTest方法，参数是array
 - 第0条的action属性 @update
 - 第0条的table属性 @bug
 - 第0条的conditionType属性 @data
- 执行convertTest模块的processWorkflowHooksTest方法，参数是array
 - 第0条的action属性 @update
 - 第0条的table属性 @story
- 执行convertTest模块的processWorkflowHooksTest方法，参数是array
 - 第0条的action属性 @update
 - 第0条的table属性 @task
 - 第0条的conditionType属性 @data
 - 第0条的sqlResult属性 @empty

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$convertTest = new convertTaoTest();

r($convertTest->processWorkflowHooksTest(array(), array(), 'bug')) && p() && e('0');
r($convertTest->processWorkflowHooksTest(array('results' => array()), array(), 'bug')) && p() && e('0');
r($convertTest->processWorkflowHooksTest(array('results' => array('unconditional-result' => array('@attributes' => array('step' => '1')))), array('1' => 'open'), 'bug')) && p('0:action,table,conditionType') && e('update,bug,data');
r($convertTest->processWorkflowHooksTest(array('results' => array('unconditional-result' => array('@attributes' => array('step' => '2')))), array('2' => array('resolved', 'closed')), 'story')) && p('0:action,table') && e('update,story');
r($convertTest->processWorkflowHooksTest(array('results' => array('unconditional-result' => array('@attributes' => array('step' => '3')))), array('3' => 'doing'), 'task')) && p('0:action,table,conditionType,sqlResult') && e('update,task,data,empty');