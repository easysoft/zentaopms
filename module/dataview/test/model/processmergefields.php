#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::processMergeFields();
timeout=0
cid=0

- 执行dataviewTest模块的processMergeFieldsTest方法，参数是'user', 'account', 'account', array 
 -  @用户名
 - 属性1 @user
- 执行dataviewTest模块的processMergeFieldsTest方法，参数是'flow_project', 'name', 'name', array 
 -  @项目名称
 - 属性1 @project
- 执行dataviewTest模块的processMergeFieldsTest方法，参数是'zt_flow_task', 'title', 'title', array 
 -  @title
 - 属性1 @task
- 执行dataviewTest模块的processMergeFieldsTest方法，参数是'product', 'nonexistfield', 'nonexistfield', array 
 -  @nonexistfield
 - 属性1 @product
- 执行dataviewTest模块的processMergeFieldsTest方法，参数是'task', '', '', array  @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$dataviewTest = new dataviewModelTest();

r($dataviewTest->processMergeFieldsTest('user', 'account', 'account', array())) && p('0,1') && e('用户名,user');
r($dataviewTest->processMergeFieldsTest('flow_project', 'name', 'name', array())) && p('0,1') && e('项目名称,project');
r($dataviewTest->processMergeFieldsTest('zt_flow_task', 'title', 'title', array())) && p('0,1') && e('title,task');
r($dataviewTest->processMergeFieldsTest('product', 'nonexistfield', 'nonexistfield', array())) && p('0,1') && e('nonexistfield,product');
r($dataviewTest->processMergeFieldsTest('task', '', '', array())) && p('0') && e('~~');