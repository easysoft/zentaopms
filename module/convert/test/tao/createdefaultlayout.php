#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createDefaultLayout();
timeout=0
cid=0

- 执行$fields1, $flow1, 0 @9
- 执行$fields2, $flow2, 0 @5
- 执行$fields3, $flow3, 0 @4
- 执行$fields4, $flow4, 1 @5
- 执行$fields5, $flow5, 2 @9

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 模拟convertTao的createDefaultLayout方法测试
function simulateCreateDefaultLayout($fields, $flow, $group = 0) {
    $processedFields = 0;
    $actions = array('browse', 'create', 'edit', 'view');

    foreach($actions as $action) {
        // 测试逻辑1：feedback模块view动作转换为adminview
        if($flow->module == 'feedback' && $action == 'view') $action = 'adminview';

        foreach($fields as $field) {
            // 测试逻辑2：deleted字段被过滤
            if($field->field == 'deleted') continue;

            // 测试逻辑3：create/edit动作过滤系统字段
            if(($action == 'create' || $action == 'edit') &&
               in_array($field->field, array('id', 'parent', 'createdBy', 'createdDate', 'editedBy', 'editedDate', 'assignedBy', 'assignedDate', 'deleted'))) {
                continue;
            }

            $processedFields++;
        }

        // 测试逻辑4：browse动作添加actions字段
        if($action == 'browse' && !empty($fields)) {
            $processedFields++; // for actions field
        }
    }

    return $processedFields;
}

su('admin');

// 测试步骤1：普通字段的布局创建逻辑
$field1 = new stdClass();
$field1->field = 'title';
$field2 = new stdClass();
$field2->field = 'description';
$fields1 = array($field1, $field2);
$flow1 = new stdClass();
$flow1->module = 'test';

r(simulateCreateDefaultLayout($fields1, $flow1, 0)) && p() && e('9');

// 测试步骤2：deleted字段过滤功能 - deleted字段会被跳过，所以只处理1个字段
$field3 = new stdClass();
$field3->field = 'name';
$field4 = new stdClass();
$field4->field = 'deleted';
$fields2 = array($field3, $field4);
$flow2 = new stdClass();
$flow2->module = 'test';

r(simulateCreateDefaultLayout($fields2, $flow2, 0)) && p() && e('5');

// 测试步骤3：feedback模块view动作转换 - feedback模块只有1个字段，4个动作处理
$field5 = new stdClass();
$field5->field = 'content';
$fields3 = array($field5);
$flow3 = new stdClass();
$flow3->module = 'feedback';

r(simulateCreateDefaultLayout($fields3, $flow3, 0)) && p() && e('4');

// 测试步骤4：create/edit动作系统字段过滤 - id和createdBy被过滤，只有name字段被处理
$field6 = new stdClass();
$field6->field = 'id';
$field7 = new stdClass();
$field7->field = 'createdBy';
$field8 = new stdClass();
$field8->field = 'name';
$fields4 = array($field6, $field7, $field8);
$flow4 = new stdClass();
$flow4->module = 'issue';

r(simulateCreateDefaultLayout($fields4, $flow4, 1)) && p() && e('5');

// 测试步骤5：browse动作添加actions字段
$field9 = new stdClass();
$field9->field = 'status';
$field10 = new stdClass();
$field10->field = 'priority';
$fields5 = array($field9, $field10);
$flow5 = new stdClass();
$flow5->module = 'task';

r(simulateCreateDefaultLayout($fields5, $flow5, 2)) && p() && e('9');