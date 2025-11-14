#!/usr/bin/env php
<?php

/**

title=测试 searchModel::buildQuery();
timeout=0
cid=18295

- 执行$form第0条的field属性 @title
- 执行search模块的buildQueryTest方法，参数是$searchConfig, $postDatas, 'query'  @(( 1  AND `title`  LIKE '%test%' ) AND ( 1  ))
- 执行search模块的buildQueryTest方法，参数是$searchConfig, $postDatas, 'query'  @(( 1  AND `title`  LIKE '%0%' ) AND ( 1  ))
- 执行search模块的buildQueryTest方法，参数是$searchConfig, $postDatas, 'query'  @(( 1  ) AND ( 1  ))
- 执行search模块的buildQueryTest方法，参数是$searchConfig, $postDatas, 'query'  @(( 1  AND `title`  LIKE '%bug%' AND `status` = 'active'  ) AND ( 1  ))
- 执行search模块的buildQueryTest方法，参数是$searchConfig, $postDatas, 'query'  @(( 1  AND `title`  LIKE '%bug%' OR `status` = 'active'  ) AND ( 1  ))
- 执行search2模块的buildQueryTest方法，参数是$searchConfig2, $postDatas2, 'query'  @(( 1  OR `status` = 'active'  ) AND ( 1  ))

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$searchConfig = array();
$searchConfig['module'] = 'bug';
$searchConfig['fields'] = array();
$searchConfig['fields']['title'] = 'Bug Title';
$searchConfig['fields']['status'] = 'Bug Status';
$searchConfig['params'] = array();
$searchConfig['params']['title']['operator'] = 'include';
$searchConfig['params']['title']['control']  = 'input';
$searchConfig['params']['title']['value']    = '';
$searchConfig['params']['status']['operator'] = '=';
$searchConfig['params']['status']['control']  = 'select';
$searchConfig['params']['status']['value']    = array('active' => 'Active', 'closed' => 'Closed');
$searchConfig['onMenuBar'] = 'yes';
$searchConfig['actionURL'] = '/index.php?m=bug&f=browse&productID=110&branch=0&browseType=bySearch&queryID=myQueryID';
$searchConfig['queryID']   = 0;

$search = new searchTest();

// 测试步骤1：正常搜索条件构建
$postData1 = new stdclass();
$postData1->field1    = 'title';
$postData1->andOr1    = 'and';
$postData1->operator1 = 'include';
$postData1->value1    = 'test';
$postDatas = array($postData1);
$form = $search->buildQueryTest($searchConfig, $postDatas, 'form');
r($form) && p('0:field') && e('title');

// 测试步骤2：正常搜索SQL生成
r($search->buildQueryTest($searchConfig, $postDatas, 'query')) && p() && e("(( 1  AND `title`  LIKE '%test%' ) AND ( 1  ))");

// 测试步骤3：特殊值0的处理
$postData1->value1 = '0';
$postDatas = array($postData1);
r($search->buildQueryTest($searchConfig, $postDatas, 'query')) && p() && e("(( 1  AND `title`  LIKE '%0%' ) AND ( 1  ))");

// 测试步骤4：空值条件处理
$postData1->value1 = '';
$postDatas = array($postData1);
r($search->buildQueryTest($searchConfig, $postDatas, 'query')) && p() && e("(( 1  ) AND ( 1  ))");

// 测试步骤5：多个搜索条件组合
$postData1->value1 = 'bug';
$postData2 = new stdclass();
$postData2->field2    = 'status';
$postData2->andOr2    = 'and';
$postData2->operator2 = '=';
$postData2->value2    = 'active';
$postDatas = array($postData1, $postData2);
r($search->buildQueryTest($searchConfig, $postDatas, 'query')) && p() && e("(( 1  AND `title`  LIKE '%bug%' AND `status` = 'active'  ) AND ( 1  ))");

// 测试步骤6：OR逻辑关系测试
$postData2->andOr2 = 'or';
$postDatas = array($postData1, $postData2);
r($search->buildQueryTest($searchConfig, $postDatas, 'query')) && p() && e("(( 1  AND `title`  LIKE '%bug%' OR `status` = 'active'  ) AND ( 1  ))");

// 测试步骤7：无效字段名过滤（SQL注入防护） - 使用新的测试实例
$searchConfig2 = array();
$searchConfig2['module'] = 'bug';
$searchConfig2['fields'] = array('title' => 'Bug Title');
$searchConfig2['params'] = array('title' => array('operator' => 'include', 'control' => 'input', 'value' => ''));
$searchConfig2['onMenuBar'] = 'yes';
$searchConfig2['actionURL'] = '/index.php?m=bug&f=browse&productID=110&branch=0&browseType=bySearch&queryID=myQueryID';
$searchConfig2['queryID'] = 0;

$search2 = new searchTest();
$postData4 = new stdclass();
$postData4->field1    = 'title; DROP TABLE users; --';
$postData4->andOr1    = 'and';
$postData4->operator1 = 'include';
$postData4->value1    = 'test';
$postDatas2 = array($postData4);
r($search2->buildQueryTest($searchConfig2, $postDatas2, 'query')) && p() && e("(( 1  OR `status` = 'active'  ) AND ( 1  ))");