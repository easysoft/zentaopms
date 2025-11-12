#!/usr/bin/env php
<?php

/**

title=测试 searchZen::setOptionFields();
timeout=0
cid=0

- 测试步骤1:正常输入两个字段 @1
- 测试步骤2:测试空字段输入 @0
- 测试步骤3:测试单个字段输入 @1
- 测试步骤4:测试带values参数的字段 @1
- 测试步骤5:验证不同fieldParams格式 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$searchTest = new searchZenTest();

$fields1 = array('id' => 'ID', 'name' => '名称');
$fieldParams1 = array(
    'id' => array('control' => 'input', 'operator' => 'include'),
    'name' => array('control' => 'input', 'operator' => 'include')
);

$fields2 = array();
$fieldParams2 = array();

$fields3 = array('title' => '标题');
$fieldParams3 = array(
    'title' => array('control' => 'input', 'operator' => 'include')
);

$fields4 = array('status' => '状态');
$fieldParams4 = array(
    'status' => array('control' => 'select', 'operator' => 'equal', 'values' => array('open' => '激活', 'closed' => '已关闭'))
);

$fields5 = array('priority' => '优先级', 'type' => '类型');
$fieldParams5 = array(
    'priority' => array('control' => 'select', 'operator' => 'equal'),
    'type' => array('control' => 'select', 'operator' => 'equal')
);

r(is_array($searchTest->setOptionFieldsTest($fields1, $fieldParams1))) && p() && e('1'); // 测试步骤1:正常输入两个字段
r($searchTest->setOptionFieldsTest($fields2, $fieldParams2)) && p() && e('0'); // 测试步骤2:测试空字段输入
r(is_array($searchTest->setOptionFieldsTest($fields3, $fieldParams3))) && p() && e('1'); // 测试步骤3:测试单个字段输入
r(is_array($searchTest->setOptionFieldsTest($fields4, $fieldParams4))) && p() && e('1'); // 测试步骤4:测试带values参数的字段
r(is_array($searchTest->setOptionFieldsTest($fields5, $fieldParams5))) && p() && e('1'); // 测试步骤5:验证不同fieldParams格式