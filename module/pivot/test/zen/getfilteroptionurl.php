#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::getFilterOptionUrl();
timeout=0
cid=0

- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter1 属性method @post
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter2, '', $fieldSettings2 第data条的type属性 @options
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter3, '', array 第data条的saveAs属性 @test_field
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter4, 'SELECT * FROM user', $fieldSettings4 
 - 第data条的values属性 @1
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter5, 'SELECT id, realname FROM user', $fieldSettings5 第data条的field属性 @realname

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

// 测试步骤1：from='query'的正常情况
$filter1 = array(
    'field' => 'status',
    'from' => 'query',
    'typeOption' => 'user',
    'default' => 'active'
);
r($pivotTest->getFilterOptionUrlTest($filter1)) && p('method') && e('post');

// 测试步骤2：from='result'的正常情况，fieldType='options'
$filter2 = array(
    'field' => 'priority',
    'from' => 'result',
    'default' => '1'
);
$fieldSettings2 = array(
    'priority' => array(
        'type' => 'options',
        'object' => 'bug',
        'field' => 'priority'
    )
);
r($pivotTest->getFilterOptionUrlTest($filter2, '', $fieldSettings2)) && p('data:type') && e('options');

// 测试步骤3：空filter数组的边界情况（最小必需字段）
$filter3 = array(
    'field' => 'test_field'
);
r($pivotTest->getFilterOptionUrlTest($filter3, '', array())) && p('data:saveAs') && e('test_field');

// 测试步骤4：数组类型的default值处理
$filter4 = array(
    'field' => 'assignedTo',
    'from' => 'result',
    'default' => array('1', '2', '3')
);
$fieldSettings4 = array(
    'assignedTo' => array(
        'type' => 'select',
        'object' => 'user',
        'field' => 'account'
    )
);
r($pivotTest->getFilterOptionUrlTest($filter4, 'SELECT * FROM user', $fieldSettings4)) && p('data:values') && e('1,2,3');

// 测试步骤5：fieldType='object'的特殊情况
$filter5 = array(
    'field' => 'owner',
    'from' => 'result',
    'default' => 'admin',
    'saveAs' => 'ownerName'
);
$fieldSettings5 = array(
    'owner' => array(
        'type' => 'object',
        'object' => 'user',
        'field' => 'realname'
    )
);
r($pivotTest->getFilterOptionUrlTest($filter5, 'SELECT id,realname FROM user', $fieldSettings5)) && p('data:field') && e('realname');