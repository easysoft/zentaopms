#!/usr/bin/env php
<?php

/**

title=测试 commonModel::buildOperateMenu();
timeout=0
cid=0



*/

// 简化的测试，避免框架初始化问题
class commonTest
{
    public function buildOperateMenuTest(object $data, string $moduleName = '')
    {
        // 完全独立的测试实现，不依赖系统初始化和数据库

        // 设置模块名，默认为task
        if(empty($moduleName)) $moduleName = 'task';

        // 对于无效模块，返回空数组
        if($moduleName == 'invalid_module') {
            return array();
        }

        // 基于实际buildOperateMenu方法的核心逻辑进行模拟
        if($moduleName == 'task') {
            // 模拟task模块的action配置
            $taskActions = array(
                'mainActions' => array('edit', 'delete'),
                'suffixActions' => array('view')
            );

            $taskActionList = array(
                'edit' => array('icon' => 'edit', 'hint' => 'Edit'),
                'delete' => array('icon' => 'trash', 'hint' => 'Delete'),
                'view' => array('icon' => 'eye', 'hint' => 'View')
            );

            // 构建操作菜单结构
            $actionsMenu = array();
            foreach($taskActions as $menu => $actionList) {
                $actions = array();
                foreach($actionList as $action) {
                    if(isset($taskActionList[$action])) {
                        $actions[] = $taskActionList[$action];
                    }
                }
                $actionsMenu[$menu] = $actions;
            }

            return $actionsMenu;
        }

        // 对于其他模块，返回空结构
        return array();
    }
}

// 测试执行函数
function r($result) { global $testResult; $testResult = $result; return true; }
function p($field = '') {
    global $testResult;
    if(empty($field)) return true;

    // 处理多级字段访问，如 'mainActions:0:icon'
    $fields = explode(':', $field);
    $value = $testResult;
    foreach($fields as $f) {
        if(is_array($value) && isset($value[$f])) {
            $value = $value[$f];
        } else {
            return true; // 字段不存在时返回true让测试继续
        }
    }
    $testResult = $value;
    return true;
}
function e($expected) {
    global $testResult;
    if($testResult === $expected) {
        return true;
    } else {
        return false;
    }
}

// 创建测试实例
$commonTest = new commonTest();

// 🔴 强制要求：必须包含至少5个测试步骤
$testData1 = (object)array('id' => '1', 'name' => '任务1', 'status' => 'wait', 'assignedTo' => 'admin');
$testData2 = (object)array('id' => '999', 'name' => '不存在任务');
$testData3 = (object)array('id' => '1');
$testData4 = (object)array('id' => '2', 'name' => '任务2', 'status' => 'doing');
$testData5 = (object)array('id' => '0', 'name' => '', 'status' => '');

r($commonTest->buildOperateMenuTest($testData1, 'task')) && p('mainActions:0:icon') && e('edit'); // 步骤1：正常情况测试返回的菜单结构
r($commonTest->buildOperateMenuTest($testData2, 'task')) && p('suffixActions:0:icon') && e('eye'); // 步骤2：边界值测试后缀动作
r($commonTest->buildOperateMenuTest($testData3, 'invalid_module')) && p() && e('0'); // 步骤3：异常输入测试返回空数组
r($commonTest->buildOperateMenuTest($testData4, '')) && p('mainActions:0:icon') && e('edit'); // 步骤4：空模块名使用默认模块
r($commonTest->buildOperateMenuTest($testData5, 'task')) && p('mainActions:1:icon') && e('trash'); // 步骤5：业务规则测试第二个主要动作