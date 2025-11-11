#!/usr/bin/env php
<?php

/**

title=测试 searchZen::setOptions();
timeout=0
cid=0

- 测试步骤1:正常输入完整参数属性savedQueryTitle @已保存的查询条件
- 测试步骤2:测试空字段和fieldParams属性searchBtnText @搜索
- 测试步骤3:测试带savedQuery的情况 @2
- 测试步骤4:验证options对象包含fields属性 @1
- 测试步骤5:验证options对象包含operators属性 @1
- 测试步骤6:验证options对象包含andOr属性 @1
- 测试步骤7:验证options对象包含formConfig属性第formConfig条的method属性 @post

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$searchTest = new searchZenTest();

$fields1 = array('id' => 'ID', 'name' => '名称', 'status' => '状态');
$fieldParams1 = array(
    'id' => array('control' => 'input', 'operator' => 'include'),
    'name' => array('control' => 'input', 'operator' => 'include'),
    'status' => array('control' => 'select', 'operator' => 'equal', 'values' => array('open' => '激活', 'closed' => '已关闭'))
);
$queries1 = array();

$fields2 = array();
$fieldParams2 = array();
$queries2 = array();

$query1 = new stdclass();
$query1->id = 1;
$query1->title = '测试查询1';
$query1->form = 'test';
$query1->sql = 'test sql';

$query2 = new stdclass();
$query2->id = 2;
$query2->title = '测试查询2';
$query2->form = 'test2';
$query2->sql = 'test sql2';

$queries3 = array($query1, $query2);

$fields4 = array('title' => '标题', 'priority' => '优先级');
$fieldParams4 = array(
    'title' => array('control' => 'input', 'operator' => 'include'),
    'priority' => array('control' => 'select', 'operator' => 'equal')
);
$queries4 = array();

$fields5 = array('id' => 'ID');
$fieldParams5 = array(
    'id' => array('control' => 'input', 'operator' => 'include')
);
$queries5 = array();

$result1 = $searchTest->setOptionsTest($fields1, $fieldParams1, $queries1);
$result2 = $searchTest->setOptionsTest($fields2, $fieldParams2, $queries2);
$result3 = $searchTest->setOptionsTest($fields1, $fieldParams1, $queries3);
$result4 = $searchTest->setOptionsTest($fields4, $fieldParams4, $queries4);
$result5 = $searchTest->setOptionsTest($fields4, $fieldParams4, $queries4);
$result6 = $searchTest->setOptionsTest($fields5, $fieldParams5, $queries5);
$result7 = $searchTest->setOptionsTest($fields1, $fieldParams1, $queries1);

r($result1) && p('savedQueryTitle') && e('已保存的查询条件'); // 测试步骤1:正常输入完整参数
r($result2) && p('searchBtnText') && e('搜索'); // 测试步骤2:测试空字段和fieldParams
r(count($result3->savedQuery)) && p() && e('2'); // 测试步骤3:测试带savedQuery的情况
r(is_array($result4->fields)) && p() && e('1'); // 测试步骤4:验证options对象包含fields属性
r(is_array($result5->operators)) && p() && e('1'); // 测试步骤5:验证options对象包含operators属性
r(is_array($result6->andOr)) && p() && e('1'); // 测试步骤6:验证options对象包含andOr属性
r($result7) && p('formConfig:method') && e('post'); // 测试步骤7:验证options对象包含formConfig属性