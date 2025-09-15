#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::assignViewData();
timeout=0
cid=0

- 执行$testPlan属性plan @1
- 执行$testPlan属性gradeGroupSet @set
- 执行$testPlan属性usersSet @set
- 执行$testPlan属性plansSet @set
- 执行$testPlan属性modulesSet @set

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

// 简化测试方法，直接测试核心功能
function testAssignViewDataSimple($plan) {
    global $tester;
    
    try {
        $objectZen = $tester->loadZen('productplan');
        
        $reflection = new ReflectionClass($objectZen);
        $method = $reflection->getMethod('assignViewData');
        $method->setAccessible(true);
        $method->invoke($objectZen, $plan);
        
        $result = array();
        $result['plan'] = isset($objectZen->view->plan) ? $objectZen->view->plan->id : null;
        $result['gradeGroupSet'] = isset($objectZen->view->gradeGroup) ? 'set' : 'not_set';
        $result['usersSet'] = isset($objectZen->view->users) ? 'set' : 'not_set';
        $result['plansSet'] = isset($objectZen->view->plans) ? 'set' : 'not_set';
        $result['modulesSet'] = isset($objectZen->view->modules) ? 'set' : 'not_set';
        
        return $result;
    } catch (Exception $e) {
        return array('error' => $e->getMessage());
    }
}

$testPlan = (object)array('id' => 1, 'product' => 1, 'branch' => 0, 'parent' => 0);

r(testAssignViewDataSimple($testPlan)) && p('plan') && e('1');
r(testAssignViewDataSimple($testPlan)) && p('gradeGroupSet') && e('set');
r(testAssignViewDataSimple($testPlan)) && p('usersSet') && e('set');
r(testAssignViewDataSimple($testPlan)) && p('plansSet') && e('set');
r(testAssignViewDataSimple($testPlan)) && p('modulesSet') && e('set');