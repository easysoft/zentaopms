#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 searchModel->saveIndex();
timeout=0
cid=18309

- 标题非空测试第title条的0属性 @『标题』不能为空。
- 测试保存的查询的模块属性module @bug
- 业需使用需求的模块名属性module @story
- 用需使用需求的模块名属性module @story
- 需求使用需求的模块名属性module @story

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

global $app;
include($app->getModuleRoot() . '/search/control.php');
$app->control = new search();

$search = new searchModelTest();
r($search->saveQueryTest($module, $title[0], $where, $queryForm))       && p('title:0') && e('『标题』不能为空。'); //标题非空测试
r($search->saveQueryTest($module, $title[1], $where, $queryForm))       && p('module') && e('bug');   //测试保存的查询的模块
r($search->saveQueryTest('epic',  $title[1], $where, $queryForm))       && p('module') && e('story'); //业需使用需求的模块名
r($search->saveQueryTest('requirement', $title[1], $where, $queryForm)) && p('module') && e('story'); //用需使用需求的模块名
r($search->saveQueryTest('story', $title[1], $where, $queryForm))       && p('module') && e('story'); //需求使用需求的模块名