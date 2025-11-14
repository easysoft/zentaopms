#!/usr/bin/env php
<?php

/**

title=测试 executionZen::assignBugVars();
timeout=0
cid=16401

- 执行executionzenTest模块的assignBugVarsTest方法，参数是1, 1, 1, '', array 
 - 属性title @执行1-Bug列表
 - 属性productID @1
 - 属性orderBy @id_desc
- 执行executionzenTest模块的assignBugVarsTest方法，参数是2, 2, 0, '', array 
 - 属性title @执行2-Bug列表
 - 属性orderBy @id_asc
 - 属性switcherObjectID @1
- 执行executionzenTest模块的assignBugVarsTest方法，参数是3, 3, 2, '1', array 
 - 属性title @执行3-Bug列表
 - 属性productID @2
 - 属性moduleID @5
 - 属性type @bymodule
- 执行executionzenTest模块的assignBugVarsTest方法，参数是4, 4, 0, '', array 
 - 属性title @执行4-Bug列表
 - 属性productID @0
 - 属性orderBy @pri_desc
- 执行executionzenTest模块的assignBugVarsTest方法，参数是5, 5, 3, '2', array 
 - 属性title @执行5-Bug列表
 - 属性productID @3
 - 属性buildID @1
 - 属性branchID @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

zenData('project')->loadYaml('project_assignbugvars', false, 2)->gen(10);
zenData('product')->loadYaml('product_assignbugvars', false, 2)->gen(10);
zenData('user')->loadYaml('zt_user', false, 2)->gen(5);
zenData('bug')->loadYaml('bug_assignbugvars', false, 2)->gen(20);
zenData('build')->gen(5);
zenData('module')->gen(10);

su('admin');

$executionzenTest = new executionZenTest();

r($executionzenTest->assignBugVarsTest(1, 1, 1, '', array(), 'id_desc', 'all', 0, '', array(), new stdClass())) && p('title,productID,orderBy') && e('执行1-Bug列表,1,id_desc');
r($executionzenTest->assignBugVarsTest(2, 2, 0, '', array(1 => (object)array('id'=>1), 2 => (object)array('id'=>2)), 'id_asc', 'all', 0, '', array(), new stdClass())) && p('title,orderBy,switcherObjectID') && e('执行2-Bug列表,id_asc,1');
r($executionzenTest->assignBugVarsTest(3, 3, 2, '1', array((object)array('id'=>2)), 'status_asc', 'bymodule', 5, '', array(), new stdClass())) && p('title,productID,moduleID,type') && e('执行3-Bug列表,2,5,bymodule');
r($executionzenTest->assignBugVarsTest(4, 4, 0, '', array(), 'pri_desc', 'all', 0, '', array(), new stdClass())) && p('title,productID,orderBy') && e('执行4-Bug列表,0,pri_desc');
r($executionzenTest->assignBugVarsTest(5, 5, 3, '2', array((object)array('id'=>3)), 'openedBy_asc', 'all', 0, '1', array(), new stdClass())) && p('title,productID,buildID,branchID') && e('执行5-Bug列表,3,1,2');