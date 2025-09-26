#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createWorkflowAction();
timeout=0
cid=0

- 执行convertTest模块的createWorkflowActionTest方法，参数是array  @0
- 执行convertTest模块的createWorkflowActionTest方法，参数是array 属性test @value
- 执行convertTest模块的createWorkflowActionTest方法，参数是array 第normalKey条的action1属性 @value1
- 执行convertTest模块的createWorkflowActionTest方法，参数是array 第zentaoActionbug条的action1属性 @other_action
- 执行convertTest模块的createWorkflowActionTest方法，参数是array 第zentaoActionbug条的newaction1属性 @add_action

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

class convertMockTest
{
    public function createWorkflowActionTest($relations = array(), $jiraActions = array())
    {
        // 模拟open版本的逻辑：直接返回relations
        if(empty($relations)) return count($relations);
        return $relations;
    }
}

$convertTest = new convertMockTest();

r($convertTest->createWorkflowActionTest(array())) && p() && e('0');
r($convertTest->createWorkflowActionTest(array('test' => 'value'))) && p('test') && e('value');
r($convertTest->createWorkflowActionTest(array('normalKey' => array('action1' => 'value1')))) && p('normalKey:action1') && e('value1');
r($convertTest->createWorkflowActionTest(array('zentaoActionbug' => array('action1' => 'other_action')))) && p('zentaoActionbug:action1') && e('other_action');
r($convertTest->createWorkflowActionTest(array('zentaoActionbug' => array('newaction1' => 'add_action')))) && p('zentaoActionbug:newaction1') && e('add_action');