#!/usr/bin/env php
<?php

/**

title=测试 adminModel::getHasPrivLink();
timeout=0
cid=0

- 测试步骤1：超级管理员访问正常菜单链接
 - 属性0 @company
 - 属性1 @index
- 测试步骤2：没有链接字段的菜单 @0
- 测试步骤3：超级管理员优先使用主链接
 - 属性0 @invalid
 - 属性1 @method
- 测试步骤4：自定义模块索引权限测试
 - 属性0 @custom
 - 属性1 @index
- 测试步骤5：空链接字段的菜单 @0
- 测试步骤6：主链接存在时不会回退到备选无效链接
 - 属性0 @invalid
 - 属性1 @method
- 测试步骤7：主链接存在且备选为空时仍返回主链接
 - 属性0 @invalid
 - 属性1 @method

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$adminTest = new adminModelTest();

r($adminTest->getHasPrivLinkTest(array('link' => 'System|company|index|'))) && p('0,1') && e('company,index');
r($adminTest->getHasPrivLinkTest(array())) && p() && e('0');
r($adminTest->getHasPrivLinkTest(array('link' => 'Invalid|invalid|method|', 'links' => array('company|index|')))) && p('0,1') && e('invalid,method');
r($adminTest->getHasPrivLinkTest(array('link' => 'System|custom|index|'))) && p('0,1') && e('custom,index');
r($adminTest->getHasPrivLinkTest(array('link' => ''))) && p() && e('0');
r($adminTest->getHasPrivLinkTest(array('link' => 'Invalid|invalid|method|', 'links' => array('Invalid|invalid|method|')))) && p('0,1') && e('invalid,method');
r($adminTest->getHasPrivLinkTest(array('link' => 'Invalid|invalid|method|', 'links' => array()))) && p('0,1') && e('invalid,method');
