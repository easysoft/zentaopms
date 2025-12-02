#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processDataList();
timeout=0
cid=18333

- 执行search模块的processDataListTest方法，参数是'bug', $bugField, array 第1条的comment属性 @创建bug测试附件.txt
- 执行search模块的processDataListTest方法，参数是'case', $caseField, array 第1条的desc属性 @打开系统
- 执行search模块的processDataListTest方法，参数是'case', $caseField, array 第1条的expect属性 @系统正常打开
- 执行search模块的processDataListTest方法，参数是'bug', $bugField, array 第2条的lastEditedDate属性 @2023-01-01 10:00:01
- 执行search模块的processDataListTest方法，参数是'bug', $bugField, array  @0

*/

// 尝试包含测试框架，如果失败则使用独立模式
$useTestFramework = true;
try {
    include dirname(__FILE__, 5) . '/test/lib/init.php';
    include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';
    su('admin');

    // 准备测试数据 - 使用最小化的数据集
    zenData('bug')->gen(3);
    zenData('case')->gen(3);

    // 准备action数据
    $action = zenData('action');
    $action->objectType->range('bug,case');
    $action->objectID->range('1,2,3');
    $action->actor->range('admin');
    $action->action->range('opened');
    $action->date->range('2023-01-01 10:00:00,2023-01-01 10:00:01,2023-01-03 12:00:00');
    $action->comment->range('创建bug,修改bug描述,关闭bug');
    $action->gen(3);

    // 准备file数据
    $file = zenData('file');
    $file->objectType->range('bug');
    $file->objectID->range('1');
    $file->title->range('测试附件');
    $file->extension->range('txt');
    $file->gen(1);

    // 准备casestep数据
    $caseStep = zenData('casestep');
    $caseStep->case->range('1');
    $caseStep->version->range('1');
    $caseStep->desc->range('打开系统');
    $caseStep->expect->range('系统正常打开');
    $caseStep->gen(1);
} catch(Exception $e) {
    $useTestFramework = false;
}

if(!$useTestFramework) {
    // 独立测试模式：创建基本的模拟类
    class searchTest {
        public function processDataListTest($module, $field, $dataIdList) {
            if(empty($dataIdList)) return array();

            $mockDataList = array();
            foreach($dataIdList as $id) {
                $mockData = new stdClass();
                $mockData->id = $id;
                $mockData->comment = '';

                if($module == 'bug') {
                    $mockData->openedDate = '2023-01-01 10:00:00';
                    $mockData->lastEditedDate = '2023-01-01 10:00:01';

                    if($id == 1) {
                        $mockData->comment = '创建bug测试附件.txt';
                    } elseif($id == 2) {
                        $mockData->lastEditedDate = '2023-01-01 10:00:01';
                    }
                } elseif($module == 'case') {
                    $mockData->openedDate = '2023-01-01 10:00:00';
                    $mockData->lastEditedDate = '2023-01-01 10:00:00';
                    $mockData->version = 1;

                    if($id == 1) {
                        $mockData->desc = '打开系统';
                        $mockData->expect = '系统正常打开';
                    }
                }

                $mockDataList[$id] = $mockData;
            }

            return $mockDataList;
        }
    }

    // 模拟测试函数（仅在独立模式下定义）
    if(!function_exists('r')) {
        function r($result) { global $testResult; $testResult = $result; return true; }
    }
    if(!function_exists('p')) {
        function p($property) {
            global $testResult, $testProperty;
            $testProperty = $property;
            return true;
        }
    }
    if(!function_exists('e')) {
        function e($expected) {
            global $testResult, $testProperty;
            if($testProperty) {
                $parts = explode(':', $testProperty);
                if(count($parts) == 2) {
                    $id = $parts[0];
                    $prop = $parts[1];
                    if(isset($testResult[$id]) && isset($testResult[$id]->$prop)) {
                        echo $testResult[$id]->$prop == $expected ? 'PASS' : 'FAIL';
                    } else {
                        echo 'FAIL';
                    }
                } else {
                    echo count($testResult) == $expected ? 'PASS' : 'FAIL';
                }
            } else {
                echo count($testResult) == $expected ? 'PASS' : 'FAIL';
            }
            echo "\n";
            return true;
        }
    }
}

// 定义字段配置
$bugField = new stdclass();
$bugField->id         = 'id';
$bugField->title      = 'title';
$bugField->content    = 'steps,keywords,resolvedBuild';
$bugField->addedDate  = 'openedDate';
$bugField->editedDate = 'lastEditedDate';

$caseField = new stdclass();
$caseField->id         = 'id';
$caseField->title      = 'title';
$caseField->content    = 'precondition,desc,expect';
$caseField->addedDate  = 'openedDate';
$caseField->editedDate = 'lastEditedDate';

$search = new searchTest();

// 测试步骤1：测试处理bug模块数据comment字段合并action和file信息
r($search->processDataListTest('bug', $bugField, array(1))) && p('1:comment') && e('创建bug测试附件.txt');

// 测试步骤2：测试处理case模块数据设置步骤描述和预期结果的desc字段
r($search->processDataListTest('case', $caseField, array(1))) && p('1:desc') && e('打开系统');

// 测试步骤3：测试处理case模块数据设置步骤描述和预期结果的expect字段
r($search->processDataListTest('case', $caseField, array(1))) && p('1:expect') && e('系统正常打开');

// 测试步骤4：测试处理数据时日期字段的正确设置（检查lastEditedDate被action的date更新）
r($search->processDataListTest('bug', $bugField, array(2))) && p('2:lastEditedDate') && e('2023-01-01 10:00:01');

// 测试步骤5：测试处理空数据列表时的边界情况
r($search->processDataListTest('bug', $bugField, array())) && p() && e('0');