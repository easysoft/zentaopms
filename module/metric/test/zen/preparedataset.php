#!/usr/bin/env php
<?php

/**

title=测试 metricZen::prepareDataset();
timeout=0
cid=17199

- 执行metricZenTest模块的prepareDatasetZenTest方法，参数是$calcGroup1 属性type @statement
- 执行$result2) ? 'object' : $result2 @object
- 执行$result3) ? 'object' : $result3 @object
- 执行$result4) ? 'object' : $result4 @object
- 执行$result5) ? 'object' : $result5 @object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

zenData('metric')->loadYaml('metric', false, 2)->gen(3);
zenData('holiday')->loadYaml('holiday', false, 1)->gen(5);

su('admin');

$metricZenTest = new metricZenTest();

class MockCalc
{
    public $id = 1;
    public $useSCM = false;
    public $reuse = false;
    public $fieldList = array('id', 'name');
    private $dao;
    private $holidays = array();
    private $weekend = 2;

    public function setDAO($dao)
    {
        $this->dao = $dao;
    }

    public function setHolidays($holidays)
    {
        $this->holidays = $holidays;
    }

    public function setWeekend($weekend)
    {
        $this->weekend = $weekend;
    }

    public function getStatement()
    {
        return (object)array('type' => 'statement', 'query' => 'SELECT * FROM test');
    }
}

// 测试步骤1：空数据源情况下的处理
$calc1 = new MockCalc();
$calcGroup1 = (object)array(
    'dataset' => '',
    'calcList' => array('test_code' => $calc1)
);
r($metricZenTest->prepareDatasetZenTest($calcGroup1)) && p('type') && e('statement');

// 测试步骤2：带有数据源的calcGroup处理
$calc2 = new MockCalc();
$calcGroup2 = (object)array(
    'dataset' => 'getProducts',
    'calcList' => array('test_code2' => $calc2)
);
$result2 = $metricZenTest->prepareDatasetZenTest($calcGroup2);
r(is_object($result2) ? 'object' : $result2) && p() && e('object');

// 测试步骤3：设置假日和周末配置
$calc3 = new MockCalc();
$calcGroup3 = (object)array(
    'dataset' => 'getUsers',
    'calcList' => array('test_code3' => $calc3)
);
$result3 = $metricZenTest->prepareDatasetZenTest($calcGroup3);
r(is_object($result3) ? 'object' : $result3) && p() && e('object');

// 测试步骤4：多个calc对象的综合处理
$calc4a = new MockCalc();
$calc4b = new MockCalc();
$calcGroup4 = (object)array(
    'dataset' => 'getTasks',
    'calcList' => array(
        'test_code4a' => $calc4a,
        'test_code4b' => $calc4b
    )
);
$result4 = $metricZenTest->prepareDatasetZenTest($calcGroup4);
r(is_object($result4) ? 'object' : $result4) && p() && e('object');

// 测试步骤5：无效数据源的错误处理
$calc5 = new MockCalc();
$calcGroup5 = (object)array(
    'dataset' => 'getDocs',
    'calcList' => array('test_code5' => $calc5)
);
$result5 = $metricZenTest->prepareDatasetZenTest($calcGroup5);
r(is_object($result5) ? 'object' : $result5) && p() && e('object');