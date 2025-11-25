#!/usr/bin/env php
<?php

/**

title=测试 chartTao::switchFieldName();
timeout=0
cid=15583

- 执行chartTest模块的switchFieldNameTest方法，参数是$fields1, $langs1, $metrics1, '0'  @状态字段
- 执行chartTest模块的switchFieldNameTest方法，参数是$fields2, $langs2, $metrics2, '0'  @Bug状态
- 执行chartTest模块的switchFieldNameTest方法，参数是$fields3, $langs3, $metrics3, '0'  @中文任务名称
- 执行chartTest模块的switchFieldNameTest方法，参数是$fields4, $langs4, $metrics4, '0'  @空字段配置
- 执行chartTest模块的switchFieldNameTest方法，参数是$fields5, $langs5, $metrics5, '0'  @项目名称中文

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

su('admin');

$chartTest = new chartTest();

// 测试步骤1：基本字段名称获取
$fields1 = array(
    'status' => array('name' => '状态字段'),
    'priority' => array('name' => '优先级字段')
);
$langs1 = array();
$metrics1 = array('status', 'priority');
r($chartTest->switchFieldNameTest($fields1, $langs1, $metrics1, '0')) && p() && e('状态字段');

// 测试步骤2：关联对象字段名称转换
$fields2 = array(
    'bugStatus' => array(
        'name' => '默认状态名',
        'object' => 'bug',
        'field' => 'status'
    )
);
$langs2 = array();
$metrics2 = array('bugStatus');
r($chartTest->switchFieldNameTest($fields2, $langs2, $metrics2, '0')) && p() && e('Bug状态');

// 测试步骤3：客户端语言优先级
$fields3 = array(
    'taskName' => array(
        'name' => '默认任务名',
        'object' => 'task',
        'field' => 'name'
    )
);
$langs3 = array(
    'taskName' => array(
        'zh-cn' => '中文任务名称',
        'en' => 'English Task Name'
    )
);
$metrics3 = array('taskName');
r($chartTest->switchFieldNameTest($fields3, $langs3, $metrics3, '0')) && p() && e('中文任务名称');

// 测试步骤4：空语言配置处理
$fields4 = array(
    'emptyField' => array(
        'name' => '空字段配置',
        'object' => 'nonexistent',
        'field' => 'nonexistent'
    )
);
$langs4 = array();
$metrics4 = array('emptyField');
r($chartTest->switchFieldNameTest($fields4, $langs4, $metrics4, '0')) && p() && e('空字段配置');

// 测试步骤5：复杂场景综合测试
$fields5 = array(
    'complexField' => array(
        'name' => '默认字段名',
        'object' => 'project',
        'field' => 'name'
    ),
    'simpleField' => array(
        'name' => '简单字段名'
    )
);
$langs5 = array(
    'complexField' => array(
        'zh-cn' => '项目名称中文',
        'en' => 'Project Name EN'
    )
);
$metrics5 = array('complexField', 'simpleField');
r($chartTest->switchFieldNameTest($fields5, $langs5, $metrics5, '0')) && p() && e('项目名称中文');