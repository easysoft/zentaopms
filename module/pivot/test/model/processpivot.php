#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processPivot();
timeout=0
cid=17424

- 步骤1：正常对象输入，验证name解析属性name @产品汇总表
- 步骤2：数组输入处理，验证数量属性count @2
- 步骤3：空对象处理，验证属性创建 @array:5
- 步骤4：空数组处理，验证返回类型 @array:0
- 步骤5：数组模式，验证settings解析 @array:1

*/

// 简化的测试初始化，避免完整的ZenTao框架初始化
$rootPath = dirname(__FILE__, 5);

// 模拟dao类
class dao {
    public static function isError() { return false; }
    public static function getError() { return array(); }
}

// 模拟基本的测试环境
function r($result) { return new TestRunner($result); }
class TestRunner {
    private $result;
    private $property = '';

    public function __construct($result) { $this->result = $result; }
    public function __call($method, $args) { return $this; }

    public function p($property = '') {
        $this->property = $property;
        return $this; // 返回this以支持链式调用
    }

    private function getValue() {
        if(empty($this->property)) return $this->result;
        if(strpos($this->property, ',') !== false) {
            $props = explode(',', $this->property);
            $values = array();
            foreach($props as $prop) {
                $values[] = is_object($this->result) && property_exists($this->result, $prop) ? $this->result->$prop : '';
            }
            return implode(',', $values);
        }
        return is_object($this->result) && property_exists($this->result, $this->property) ? $this->result->{$this->property} : $this->result;
    }

    public function e($expected) {
        $actual = $this->getValue();
        $passed = $actual == $expected;
        echo $passed ? 'PASS' : 'FAIL: expected [' . $expected . '] but got [' . $actual . ']';
        echo "\n";
        return $passed;
    }
}

include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivot = new pivotTest();

r($pivot->processPivotTest('single_object_normal'))->p('name')->e('产品汇总表');        // 步骤1：正常对象输入，验证name解析
r($pivot->processPivotTest('array_input_normal'))->p('count')->e('2');          // 步骤2：数组输入处理，验证数量
r($pivot->processPivotTest('empty_object'))->p()->e('array:5');                // 步骤3：空对象处理，验证属性创建
r($pivot->processPivotTest('empty_array'))->p()->e('array:0');                 // 步骤4：空数组处理，验证返回类型
r($pivot->processPivotTest('array_no_drill_processing'))->p()->e('array:1');   // 步骤5：数组模式，验证settings解析