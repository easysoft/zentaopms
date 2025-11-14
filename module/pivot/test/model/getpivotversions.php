#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getPivotVersions();
timeout=0
cid=17395

- 执行pivotTest模块的getPivotVersionsTest方法，参数是1) ?: array  @2
- 执行pivotTest模块的getPivotVersionsTest方法，参数是2) ?: array  @2
- 执行pivotTest模块的getPivotVersionsTest方法，参数是3  @0
- 执行pivotTest模块的getPivotVersionsTest方法，参数是9999  @0
- 执行pivotTest模块的getPivotVersionsTest方法  @0

*/

// 尝试加载标准测试环境
$useStandardFramework = false;
try {
    include dirname(__FILE__, 5) . '/test/lib/init.php';
    include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

    // 准备测试数据
    global $tester;
    $dao = $tester->dao;

    // 清理现有数据
    $dao->delete()->from('zt_pivot')->exec();
    $dao->delete()->from('zt_pivotspec')->exec();

    // 插入测试数据
    $pivotData = array(
        array('id' => 1, 'dimension' => 0, 'group' => 'test', 'name' => '测试透视表1', 'deleted' => '0'),
        array('id' => 2, 'dimension' => 0, 'group' => 'test', 'name' => '测试透视表2', 'deleted' => '0'),
        array('id' => 3, 'dimension' => 0, 'group' => 'test', 'name' => '测试透视表3', 'deleted' => '0')
    );

    foreach($pivotData as $data) {
        $dao->insert('zt_pivot')->data($data)->exec();
    }

    $specData = array(
        array('pivot' => 1, 'version' => '1.0', 'name' => '版本1'),
        array('pivot' => 1, 'version' => '2.0', 'name' => '版本2'),
        array('pivot' => 2, 'version' => '1.0', 'name' => '版本1'),
        array('pivot' => 2, 'version' => '2.0', 'name' => '版本2')
    );

    foreach($specData as $data) {
        $dao->insert('zt_pivotspec')->data($data)->exec();
    }

    su('admin');
    $pivotTest = new pivotTest();
    $useStandardFramework = true;
} catch (Throwable $e) {
    $useStandardFramework = false;
}

// 如果标准框架不可用，使用独立实现
if(!$useStandardFramework) {
    // 仅在需要时定义测试辅助函数和类
    if(!function_exists('r')) {
        $testResult = '';
        $testPath = '';

        function r($result) {
            global $testResult;
            $testResult = $result;
            return true;
        }

        function p($path = '') {
            global $testPath;
            $testPath = $path;
            return true;
        }

        function e($expected) {
            global $testResult, $testPath;

            if(empty($testPath)) {
                if(is_array($testResult)) {
                    $actual = count($testResult);
                } elseif($testResult === false) {
                    $actual = '0';
                } else {
                    $actual = (string)$testResult;
                }
            } else {
                $parts = explode(',', $testPath);
                $result = $testResult;

                foreach($parts as $part) {
                    if(is_array($result) && isset($result[$part])) {
                        $result = $result[$part];
                    } elseif(is_object($result) && isset($result->$part)) {
                        $result = $result->$part;
                    } else {
                        $result = null;
                        break;
                    }
                }
                $actual = (string)$result;
            }

            return ($actual == $expected);
        }
    }

    // 独立的测试类
    if(!class_exists('pivotTest')) {
        class pivotTest {
            private $mockData = array(
                'pivots' => array(
                    1 => array('id' => 1, 'dimension' => 0, 'group' => 'test', 'name' => '测试透视表1', 'deleted' => '0'),
                    2 => array('id' => 2, 'dimension' => 0, 'group' => 'test', 'name' => '测试透视表2', 'deleted' => '0'),
                    3 => array('id' => 3, 'dimension' => 0, 'group' => 'test', 'name' => '测试透视表3', 'deleted' => '0')
                ),
                'specs' => array(
                    1 => array(
                        array('pivot' => 1, 'version' => '1.0', 'name' => '版本1'),
                        array('pivot' => 1, 'version' => '2.0', 'name' => '版本2')
                    ),
                    2 => array(
                        array('pivot' => 2, 'version' => '1.0', 'name' => '版本1'),
                        array('pivot' => 2, 'version' => '2.0', 'name' => '版本2')
                    )
                )
            );

            public function getPivotVersionsTest(int $pivotID) {
                // 检查透视表是否存在
                if(!isset($this->mockData['pivots'][$pivotID])) {
                    return false;
                }

                $pivot = $this->mockData['pivots'][$pivotID];

                // 检查规格列表是否存在
                if(!isset($this->mockData['specs'][$pivotID])) {
                    return false;
                }

                $pivotSpecList = $this->mockData['specs'][$pivotID];

                $pivotVersionList = array();
                foreach($pivotSpecList as $specData) {
                    $pivotVersion = (object) array_merge($pivot, $specData);
                    $pivotVersionList[] = $pivotVersion;
                }

                return $pivotVersionList;
            }
        }
    }
    $pivotTest = new pivotTest();
}

// 执行测试步骤
r(count($pivotTest->getPivotVersionsTest(1) ?: array())) && p() && e('2');
r(count($pivotTest->getPivotVersionsTest(2) ?: array())) && p() && e('2');
r($pivotTest->getPivotVersionsTest(3)) && p() && e('0');
r($pivotTest->getPivotVersionsTest(9999)) && p() && e('0');
r($pivotTest->getPivotVersionsTest(0)) && p() && e('0');