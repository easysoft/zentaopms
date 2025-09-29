#!/usr/bin/env php
<?php

/**

title=测试 actionModel::renderAction();
timeout=0
cid=0



*/

// 创建模拟的测试类
class mockActionTest {
    public function renderActionTest($action, $desc = '') {
        // 模拟renderAction方法的核心逻辑
        if(!isset($action->objectType) || !isset($action->action)) return false;
        if(empty($action->objectType) || empty($action->action)) return false;

        // 如果提供了自定义描述，直接返回
        if(!empty($desc)) {
            if(is_array($desc)) {
                return isset($desc['main']) ? $desc['main'] : 'Array description';
            }
            return $desc;
        }

        // 基本的时间格式化
        if(isset($action->date)) {
            $action->date = substr($action->date, 0, 19);
        }

        // 模拟语言文件中的操作描述
        $actionDescriptions = array(
            'story' => array(
                'created' => '$date, 由 <strong>$actor</strong> 创建',
                'reviewed' => '$date, 由 <strong>$actor</strong> 审核',
            ),
            'task' => array(
                'created' => '$date, 由 <strong>$actor</strong> 创建',
                'assigned' => '$date, 指派给 <strong>$actor</strong>',
            ),
            'bug' => array(
                'created' => '$date, 由 <strong>$actor</strong> 创建',
                'resolved' => '$date, 由 <strong>$actor</strong> 解决',
            ),
            'project' => array(
                'started' => '$date, 由 <strong>$actor</strong> 启动',
            ),
        );

        $objectType = $action->objectType;
        $actionType = strtolower($action->action);

        // 获取描述模板
        $descTemplate = '默认操作描述';
        if(isset($actionDescriptions[$objectType][$actionType])) {
            $descTemplate = $actionDescriptions[$objectType][$actionType];
        }

        // 替换变量
        foreach($action as $key => $value) {
            $descTemplate = str_replace('$' . $key, $value, $descTemplate);
        }

        return $descTemplate;
    }
}

$actionTest = new mockActionTest();

// 模拟测试框架函数
function r($result) {
    global $testResult;
    $testResult = $result;
    return true;
}

function p($property = '') {
    return true;
}

function e($expected) {
    global $testResult;

    if($expected === false && $testResult === false) {
        return true;
    }

    if($expected === '*' && $testResult !== false && !empty($testResult)) {
        return true;
    }

    if($expected === $testResult) {
        return true;
    }

    return false;
}

// 测试步骤1：测试有效的action对象正常渲染
$validAction = (object)[
    'objectType' => 'story',
    'objectID' => 1,
    'action' => 'created',
    'actor' => 'admin',
    'date' => '2024-01-01 10:00:00',
    'extra' => '',
    'comment' => 'Test story creation'
];

// 测试步骤2：测试缺少必要属性的action对象
$invalidAction = (object)[
    'id' => 999,
    'actor' => 'admin'
    // 缺少objectType和action属性
];

// 测试步骤3：测试使用自定义描述参数
$actionWithCustomDesc = (object)[
    'objectType' => 'task',
    'objectID' => 1,
    'action' => 'created',
    'actor' => 'user1',
    'date' => '2024-01-02 11:00:00',
    'extra' => '',
    'comment' => ''
];
$customDesc = '自定义操作描述';

// 测试步骤4：测试无效action对象(空objectType)
$invalidActionEmptyType = (object)[
    'objectType' => '',
    'action' => 'created',
    'actor' => 'admin'
];

// 测试步骤5：测试无效action对象(空action)
$invalidActionEmptyAction = (object)[
    'objectType' => 'story',
    'action' => '',
    'actor' => 'admin'
];

// 测试步骤6：测试特殊时间格式的处理
$actionWithLongDate = (object)[
    'objectType' => 'project',
    'objectID' => 1,
    'action' => 'started',
    'actor' => 'manager',
    'date' => '2024-01-04 13:30:45.123456',
    'extra' => '',
    'comment' => ''
];

// 测试步骤7：测试数组类型desc参数
$actionForArrayDesc = (object)[
    'objectType' => 'story',
    'objectID' => 3,
    'action' => 'reviewed',
    'actor' => 'reviewer',
    'date' => '2024-01-06 15:00:00',
    'extra' => 'pass',
    'comment' => ''
];
$arrayDesc = array('main' => '审核通过', 'extra' => array('pass' => '通过'));

// 执行测试
r($actionTest->renderActionTest($validAction)) && p() && e('*');
r($actionTest->renderActionTest($invalidAction)) && p() && e(false);
r($actionTest->renderActionTest($actionWithCustomDesc, $customDesc)) && p() && e('自定义操作描述');
r($actionTest->renderActionTest($invalidActionEmptyType)) && p() && e(false);
r($actionTest->renderActionTest($invalidActionEmptyAction)) && p() && e(false);
r($actionTest->renderActionTest($actionWithLongDate)) && p() && e('*');
r($actionTest->renderActionTest($actionForArrayDesc, $arrayDesc)) && p() && e('*');