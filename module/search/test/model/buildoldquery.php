#!/usr/bin/env php
<?php

/**

title=测试 searchModel::buildOldQuery();
timeout=0
cid=18294

- 执行search模块的buildOldQueryTest方法，参数是$searchConfig, $postData1 属性query @(( 1   AND `title`  LIKE '%test%' ) AND ( 1  ))
- 执行search模块的buildOldQueryTest方法，参数是$searchConfig, $postData2 属性query @(( 1   AND `title`  LIKE '%0%' ) AND ( 1  ))
- 执行search模块的buildOldQueryTest方法，参数是$searchConfig, $postData3 属性query @(( 1   AND `title`  LIKE '%bug%' AND `status` = 'active'  ) AND ( 1  ))
- 执行search模块的buildOldQueryTest方法，参数是$searchConfig, $postData4 属性query @(( 1   AND `title`  LIKE '%bug%' OR `status` = 'active'  ) AND ( 1  ))
- 执行search模块的buildOldQueryTest方法，参数是$searchConfig, $postData5 属性query @(( 1   AND (`openedDate` >= '2024-01-01' AND `openedDate` <= '2024-01-01 23:59:59') ) AND ( 1  ))
- 执行search模块的buildOldQueryTest方法，参数是$searchConfig, $postData6 属性query @(( 1   AND (`openedDate` < '2024-01-01' OR `openedDate` > '2024-01-01 23:59:59') ) AND ( 1  ))
- 执行search2模块的buildOldQueryTest方法，参数是$searchConfig2, $postData7 属性query @(( 1   ) AND ( 1  ))

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$searchConfig = array();
$searchConfig['module'] = 'bug';
$searchConfig['fields'] = array();
$searchConfig['fields']['title'] = 'Bug Title';
$searchConfig['fields']['status'] = 'Bug Status';
$searchConfig['fields']['openedDate'] = 'Opened Date';
$searchConfig['params'] = array();
$searchConfig['params']['title']['operator'] = 'include';
$searchConfig['params']['title']['control']  = 'input';
$searchConfig['params']['title']['value']    = '';
$searchConfig['params']['status']['operator'] = '=';
$searchConfig['params']['status']['control']  = 'select';
$searchConfig['params']['status']['value']    = array('active' => 'Active', 'closed' => 'Closed');
$searchConfig['params']['openedDate']['operator'] = '=';
$searchConfig['params']['openedDate']['control']  = 'input';
$searchConfig['params']['openedDate']['value']    = '';
$searchConfig['onMenuBar'] = 'yes';
$searchConfig['actionURL'] = '/index.php?m=bug&f=browse&productID=110&branch=0&browseType=bySearch&queryID=myQueryID';
$searchConfig['queryID']   = 0;

$search = new searchTest();

// 测试步骤1：正常搜索条件构建(include操作符)
$postData1 = array(
    'module'    => 'bug',
    'field1'    => 'title',
    'andOr1'    => 'and',
    'operator1' => 'include',
    'value1'    => 'test',
    'groupAndOr' => 'AND'
);
r($search->buildOldQueryTest($searchConfig, $postData1)) && p('query') && e("(( 1   AND `title`  LIKE '%test%' ) AND ( 1  ))");

// 测试步骤2：特殊值'0'的处理(ZERO转换)
$postData2 = array(
    'module'    => 'bug',
    'field1'    => 'title',
    'andOr1'    => 'and',
    'operator1' => 'include',
    'value1'    => '0',
    'groupAndOr' => 'AND'
);
r($search->buildOldQueryTest($searchConfig, $postData2)) && p('query') && e("(( 1   AND `title`  LIKE '%0%' ) AND ( 1  ))");

// 测试步骤3：多条件组合(AND逻辑)
$postData3 = array(
    'module'    => 'bug',
    'field1'    => 'title',
    'andOr1'    => 'and',
    'operator1' => 'include',
    'value1'    => 'bug',
    'field2'    => 'status',
    'andOr2'    => 'and',
    'operator2' => '=',
    'value2'    => 'active',
    'groupAndOr' => 'AND'
);
r($search->buildOldQueryTest($searchConfig, $postData3)) && p('query') && e("(( 1   AND `title`  LIKE '%bug%' AND `status` = 'active'  ) AND ( 1  ))");

// 测试步骤4：多条件组合(OR逻辑)
$postData4 = array(
    'module'    => 'bug',
    'field1'    => 'title',
    'andOr1'    => 'and',
    'operator1' => 'include',
    'value1'    => 'bug',
    'field2'    => 'status',
    'andOr2'    => 'or',
    'operator2' => '=',
    'value2'    => 'active',
    'groupAndOr' => 'AND'
);
r($search->buildOldQueryTest($searchConfig, $postData4)) && p('query') && e("(( 1   AND `title`  LIKE '%bug%' OR `status` = 'active'  ) AND ( 1  ))");

// 测试步骤5：日期格式的特殊处理(等于)
$postData5 = array(
    'module'    => 'bug',
    'field1'    => 'openedDate',
    'andOr1'    => 'and',
    'operator1' => '=',
    'value1'    => '2024-01-01',
    'groupAndOr' => 'AND'
);
r($search->buildOldQueryTest($searchConfig, $postData5)) && p('query') && e("(( 1   AND (`openedDate` >= '2024-01-01' AND `openedDate` <= '2024-01-01 23:59:59') ) AND ( 1  ))");

// 测试步骤6：日期格式的特殊处理(不等于)
$postData6 = array(
    'module'    => 'bug',
    'field1'    => 'openedDate',
    'andOr1'    => 'and',
    'operator1' => '!=',
    'value1'    => '2024-01-01',
    'groupAndOr' => 'AND'
);
r($search->buildOldQueryTest($searchConfig, $postData6)) && p('query') && e("(( 1   AND (`openedDate` < '2024-01-01' OR `openedDate` > '2024-01-01 23:59:59') ) AND ( 1  ))");

// 测试步骤7：SQL注入防护测试
$searchConfig2 = array();
$searchConfig2['module'] = 'bug';
$searchConfig2['fields'] = array('title' => 'Bug Title');
$searchConfig2['params'] = array('title' => array('operator' => 'include', 'control' => 'input', 'value' => ''));
$searchConfig2['onMenuBar'] = 'yes';
$searchConfig2['actionURL'] = '/index.php?m=bug&f=browse&productID=110&branch=0&browseType=bySearch&queryID=myQueryID';
$searchConfig2['queryID'] = 0;

$search2 = new searchTest();
$postData7 = array(
    'module'    => 'bug',
    'field1'    => 'title; DROP TABLE users; --',
    'andOr1'    => 'and',
    'operator1' => 'include',
    'value1'    => 'test',
    'groupAndOr' => 'AND'
);
r($search2->buildOldQueryTest($searchConfig2, $postData7)) && p('query') && e("(( 1   ) AND ( 1  ))");