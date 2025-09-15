#!/usr/bin/env php
<?php

/**

title=测试 searchModel::buildOldQuery();
timeout=0
cid=0

- 执行searchTest模块的buildOldQueryTest方法，参数是$basicConfig, $postData1 属性query @(( 1   AND `status` = 'active'  ) AND ( 1  ))
- 执行searchTest模块的buildOldQueryTest方法，参数是$basicConfig, $postData2 属性query @(( 1   AND `title`  LIKE '%test%' ) AND ( 1  ))
- 执行searchTest模块的buildOldQueryTest方法，参数是$basicConfig, $postData3 属性query @(( 1   AND `title`  NOT LIKE '%bug%' ) AND ( 1  ))
- 执行searchTest模块的buildOldQueryTest方法，参数是$dateConfig, $postData4 属性query @(( 1   AND (`openedDate` >= '2023-01-01' AND `openedDate` <= '2023-01-01 23:59:59') ) AND ( 1  ))
- 执行searchTest模块的buildOldQueryTest方法，参数是$basicConfig, $postData5 属性query @(( 1   ) AND ( 1  ))

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. zendata数据准备
zenData('userquery')->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$searchTest = new searchTest();

// 5. 测试用例 - 必须包含至少5个测试步骤

// 准备基础搜索配置
$basicConfig = array(
    'module' => 'bug',
    'fields' => array('title' => 'Title', 'status' => 'Status'),
    'params' => array(
        'title' => array('operator' => 'include', 'control' => 'input', 'values' => ''),
        'status' => array('operator' => '=', 'control' => 'select', 'values' => array('active' => 'Active', 'resolved' => 'Resolved'))
    ),
    'actionURL' => '/test'
);

// 步骤1：测试正常的等于操作符查询
$postData1 = array(
    'module' => 'bug',
    'field1' => 'status',
    'operator1' => '=',
    'value1' => 'active',
    'andOr1' => 'AND',
    'groupAndOr' => 'AND'
);
r($searchTest->buildOldQueryTest($basicConfig, $postData1)) && p('query') && e("(( 1   AND `status` = 'active'  ) AND ( 1  ))");

// 步骤2：测试包含操作符的查询
$postData2 = array(
    'module' => 'bug',
    'field1' => 'title',
    'operator1' => 'include',
    'value1' => 'test',
    'andOr1' => 'AND',
    'groupAndOr' => 'AND'
);
r($searchTest->buildOldQueryTest($basicConfig, $postData2)) && p('query') && e("(( 1   AND `title`  LIKE '%test%' ) AND ( 1  ))");

// 步骤3：测试不包含操作符的查询
$postData3 = array(
    'module' => 'bug',
    'field1' => 'title',
    'operator1' => 'notinclude',
    'value1' => 'bug',
    'andOr1' => 'AND',
    'groupAndOr' => 'AND'
);
r($searchTest->buildOldQueryTest($basicConfig, $postData3)) && p('query') && e("(( 1   AND `title`  NOT LIKE '%bug%' ) AND ( 1  ))");

// 步骤4：测试日期格式的等于查询
$dateConfig = array(
    'module' => 'bug',
    'fields' => array('openedDate' => 'Opened Date'),
    'params' => array(
        'openedDate' => array('operator' => '=', 'control' => 'input', 'values' => '')
    ),
    'actionURL' => '/test'
);
$postData4 = array(
    'module' => 'bug',
    'field1' => 'openedDate',
    'operator1' => '=',
    'value1' => '2023-01-01',
    'andOr1' => 'AND',
    'groupAndOr' => 'AND'
);
r($searchTest->buildOldQueryTest($dateConfig, $postData4)) && p('query') && e("(( 1   AND (`openedDate` >= '2023-01-01' AND `openedDate` <= '2023-01-01 23:59:59') ) AND ( 1  ))");

// 步骤5：测试无效字段名的安全检查
$postData5 = array(
    'module' => 'bug',
    'field1' => 'invalid_field_with_special_chars!',
    'operator1' => '=',
    'value1' => 'test',
    'andOr1' => 'AND',
    'groupAndOr' => 'AND'
);
r($searchTest->buildOldQueryTest($basicConfig, $postData5)) && p('query') && e("(( 1   ) AND ( 1  ))");