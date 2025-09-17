#!/usr/bin/env php
<?php

/**

title=测试 searchZen::setOptionFields();
cid=0

- 测试步骤1：正常字段参数情况 >> 期望返回正确格式的字段对象数组
- 测试步骤2：包含id字段的情况 >> 期望id字段有placeholder属性
- 测试步骤3：包含values数组的字段 >> 期望字段对象包含values属性
- 测试步骤4：空字段和参数数组 >> 期望返回空数组
- 测试步骤5：多字段混合情况 >> 期望返回多个字段对象

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$searchTest = new searchTest();

r($searchTest->setOptionFieldsTest(array('title' => '标题', 'status' => '状态'), array('title' => array('control' => 'input', 'operator' => 'include'), 'status' => array('control' => 'select', 'operator' => 'equal')))) && p('0:name') && e('title');
r($searchTest->setOptionFieldsTest(array('id' => 'ID', 'title' => '标题'), array('id' => array('control' => 'input', 'operator' => 'equal'), 'title' => array('control' => 'input', 'operator' => 'include')))) && p('0:placeholder') && e('多个id可用英文逗号分隔');
r($searchTest->setOptionFieldsTest(array('status' => '状态', 'priority' => '优先级'), array('status' => array('control' => 'select', 'operator' => 'equal', 'values' => array('active' => '激活', 'closed' => '关闭')), 'priority' => array('control' => 'select', 'operator' => 'equal')))) && p('0:values:active') && e('激活');
r($searchTest->setOptionFieldsTest(array(), array())) && p() && e('0');
r($searchTest->setOptionFieldsTest(array('title' => '标题', 'status' => '状态', 'type' => '类型'), array('title' => array('control' => 'input', 'operator' => 'include'), 'status' => array('control' => 'select', 'operator' => 'equal', 'values' => array('open' => '打开')), 'type' => array('control' => 'select', 'operator' => 'equal')))) && p() && e('3');