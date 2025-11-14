#!/usr/bin/env php
<?php

/**

title=测试 searchModel::convertQueryForm();
timeout=0
cid=18296

- 执行searchTest模块的convertQueryFormTest方法，参数是array
 - 属性field1 @id
 - 属性andOr1 @and
- 执行searchTest模块的convertQueryFormTest方法，参数是array
 - 属性field1 @id
 - 属性andOr1 @and
 - 属性operator1 @=
 - 属性value1 @1
- 执行searchTest模块的convertQueryFormTest方法，参数是array 属性groupAndOr @or
- 执行searchTest模块的convertQueryFormTest方法，参数是array  @0
- 执行searchTest模块的convertQueryFormTest方法，参数是array
 - 属性field1 @name
 - 属性field2 @status
 - 属性andOr1 @or
 - 属性andOr2 @and
- 执行searchTest模块的convertQueryFormTest方法，参数是array 属性groupAndOr @and
- 执行searchTest模块的convertQueryFormTest方法，参数是array
 - 属性groupAndOr @or
 - 属性field2 @title
 - 属性andOr2 @and

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$searchTest = new searchTest();

r($searchTest->convertQueryFormTest(array('field1' => 'id', 'andOr1' => 'and', 'operator1' => '=', 'value1' => '1'))) && p('field1,andOr1') && e('id,and');
r($searchTest->convertQueryFormTest(array(array('field' => 'id', 'andOr' => 'and', 'operator' => '=', 'value' => '1')))) && p('field1,andOr1,operator1,value1') && e('id,and,=,1');
r($searchTest->convertQueryFormTest(array(array('groupAndOr' => 'or')))) && p('groupAndOr') && e('or');
r($searchTest->convertQueryFormTest(array())) && p() && e('0');
r($searchTest->convertQueryFormTest(array(array('field' => 'name', 'andOr' => 'or', 'operator' => 'include', 'value' => 'test'), array('field' => 'status', 'andOr' => 'and', 'operator' => '=', 'value' => 'active')))) && p('field1,field2,andOr1,andOr2') && e('name,status,or,and');
r($searchTest->convertQueryFormTest(array(array('groupAndOr' => 'and')))) && p('groupAndOr') && e('and');
r($searchTest->convertQueryFormTest(array(array('groupAndOr' => 'or'), array('field' => 'title', 'andOr' => 'and', 'operator' => 'include', 'value' => 'test')))) && p('groupAndOr,field2,andOr2') && e('or,title,and');