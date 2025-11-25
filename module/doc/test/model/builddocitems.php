#!/usr/bin/env php
<?php

/**

title=测试 docModel::buildDocItems();
timeout=0
cid=16044

- 步骤1：无子文档的基本节点
 - 属性value @1
 - 属性text @根文档
- 步骤2：单层子文档
 - 属性value @1
 - 属性text @根文档
- 步骤3：多层嵌套
 - 属性value @1
 - 属性text @根文档
- 步骤4：空子文档数组
 - 属性value @2
 - 属性text @文档标题
- 步骤5：字符串类型ID
 - 属性value @10
 - 属性text @字符串ID文档

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

su('admin');

$docTest = new docTest();

r($docTest->buildDocItemsTest(1, '根文档', array())) && p('value,text') && e('1,根文档');                    // 步骤1：无子文档的基本节点
r($docTest->buildDocItemsTest(1, '根文档', array(1 => array((object)array('id' => 2, 'title' => '子文档1'))))) && p('value,text') && e('1,根文档');  // 步骤2：单层子文档
r($docTest->buildDocItemsTest(1, '根文档', array(1 => array((object)array('id' => 2, 'title' => '子文档1')), 2 => array((object)array('id' => 3, 'title' => '孙文档1'))))) && p('value,text') && e('1,根文档');  // 步骤3：多层嵌套
r($docTest->buildDocItemsTest(2, '文档标题', array())) && p('value,text') && e('2,文档标题');               // 步骤4：空子文档数组
r($docTest->buildDocItemsTest('10', '字符串ID文档', array())) && p('value,text') && e('10,字符串ID文档');   // 步骤5：字符串类型ID