#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

/**

title=测试 searchModel->saveIndex();
timeout=0
cid=1

- 标题非空测试第title条的0属性 @『标题』不能为空。
- 测试保存的查询的模块属性module @bug

*/

$module = 'bug';
$title  = array('', '激活的Bug');
$where = "(( 1   AND `status` = 'active'  ) AND ( 1  ))";

$field1 = array();
$field1['field']    = 'status';
$field1['andOr']    = 'and';
$field1['operator'] = '=';
$field1['value']    = 'active';

$field2 = array();
$field2['field']    = 'module';
$field2['andOr']    = 'and';
$field2['operator'] = 'belong';
$field2['value']    = '';

$field3 = array();
$field3['field']    = 'keywords';
$field3['andOr']    = 'and';
$field3['operator'] = 'include';
$field3['value']    = '';

$field4 = array();
$field4['field']    = 'steps';
$field4['andOr']    = 'and';
$field4['operator'] = 'include';
$field4['value']    = '';

$field5 = array();
$field5['field']    = 'assignedTo';
$field5['andOr']    = 'and';
$field5['operator'] = '=';
$field5['value']    = '';

$field6 = array();
$field6['field']    = 'resolvedBy';
$field6['andOr']    = 'and';
$field6['operator'] = '=';
$field6['value']    = '';

$field7 = array();
$field7['groupAndOr'] = 'and';

$queryForm = array($field1, $field2, $field3, $field4, $field5, $field6, $field7);

$search = new searchTest();
r($search->saveQueryTest($module, $title[0], $where, $queryForm))   && p('title:0') && e('『标题』不能为空。'); //标题非空测试
r($search->saveQueryTest($module, $title[1], $where, $queryForm))   && p('module') && e('bug');                 //测试保存的查询的模块
