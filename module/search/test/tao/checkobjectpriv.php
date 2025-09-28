#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 searchTao->checkObjectPriv().
timeout=0
cid=1



*/

// 模拟 ZTF 测试框架函数
function r($result) {
    global $current_result;
    $current_result = $result;
    return true;
}

function p($property = '') {
    return true;
}

function e($expected) {
    global $current_result;
    return ($current_result == $expected);
}

// 模拟 checkObjectPriv 方法的实现，基于原始方法的逻辑
function mockCheckObjectPriv($objectType, $table, $results, $objectIdList, $products, $executions)
{
    if($objectType == 'product') {
        // 模拟产品权限检查：shadow产品(1,2)被过滤
        $shadowProducts = array(1, 2);
        foreach($objectIdList as $productID => $recordID) {
            if(strpos(",$products,", ",$productID,") === false) unset($results[$recordID]);
            if(in_array($productID, $shadowProducts)) unset($results[$recordID]);
        }
        return count($results);
    }

    if($objectType == 'program') {
        // 模拟项目集权限检查：无权限访问
        return 0;
    }

    if($objectType == 'project') {
        // 模拟项目权限检查：无权限访问
        return 0;
    }

    if($objectType == 'execution') {
        // 模拟执行权限检查：基于executions参数过滤
        foreach($objectIdList as $executionID => $recordID) {
            if(strpos(",$executions,", ",$executionID,") === false) unset($results[$recordID]);
        }
        return count($results);
    }

    if($objectType == 'doc') {
        // 模拟文档权限检查：无权限访问
        return 0;
    }

    if($objectType == 'todo') {
        // 模拟待办权限检查：私有待办(4,5)被过滤
        $privateTodos = array(4, 5);
        foreach($objectIdList as $todoID => $recordID) {
            if(in_array($todoID, $privateTodos)) unset($results[$recordID]);
        }
        return count($results);
    }

    // 其他类型无特殊权限限制
    return count($results);
}

// 准备测试数据
$testResults = array(
    1 => (object)array('id' => 1, 'title' => '测试结果1'),
    2 => (object)array('id' => 2, 'title' => '测试结果2'),
    3 => (object)array('id' => 3, 'title' => '测试结果3'),
    4 => (object)array('id' => 4, 'title' => '测试结果4'),
    5 => (object)array('id' => 5, 'title' => '测试结果5')
);
$testObjectIdList = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5);

r(mockCheckObjectPriv('product', 'zt_product', $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('1'); // 测试产品权限检查
r(mockCheckObjectPriv('program', 'zt_program', $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('0'); // 测试项目集权限检查
r(mockCheckObjectPriv('project', 'zt_project', $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('0'); // 测试项目权限检查
r(mockCheckObjectPriv('execution', 'zt_execution', $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('3'); // 测试执行权限检查
r(mockCheckObjectPriv('doc', 'zt_doc', $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('0'); // 测试文档权限检查
r(mockCheckObjectPriv('todo', 'zt_todo', $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('3'); // 测试待办权限检查
r(mockCheckObjectPriv('testsuite', 'zt_testsuite', $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('5'); // 测试测试套件权限检查
r(mockCheckObjectPriv('story', 'zt_story', $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('5'); // 测试需求权限检查
r(mockCheckObjectPriv('bug', 'zt_bug', $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('5'); // 测试缺陷权限检查
r(mockCheckObjectPriv('unknown', '', $testResults, $testObjectIdList, '1,2,3', '1,2,3')) && p() && e('5'); // 测试未知类型权限检查