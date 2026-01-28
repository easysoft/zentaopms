#!/usr/bin/env php
<?php

/**

title=测试 adminModel::getHasPrivLink();
timeout=0
cid=0

- 测试步骤1：正常菜单有权限链接
 -  @company
 - 属性1 @index
- 测试步骤2：没有链接字段的菜单 @0
- 测试步骤3：主链接无权限但备选链接有权限
 -  @company
 - 属性1 @index
- 测试步骤4：自定义模块索引权限测试
 -  @custom
 - 属性1 @index
- 测试步骤5：空链接字段的菜单 @0
- 测试步骤6：所有链接都无权限的菜单 @0
- 测试步骤7：备选链接为空数组的菜单 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$adminTest = new adminModelTest();

r($adminTest->getHasPrivLinkTest(array('link' => 'System|company|index|'))) && p('0,1') && e('company,index');                     // 测试步骤1：正常菜单有权限链接
r($adminTest->getHasPrivLinkTest(array())) && p() && e('0');                                                                    // 测试步骤2：没有链接字段的菜单
r($adminTest->getHasPrivLinkTest(array('link' => 'Invalid|invalid|method|', 'links' => array('company|index|')))) && p('0,1') && e('company,index'); // 测试步骤3：主链接无权限但备选链接有权限
r($adminTest->getHasPrivLinkTest(array('link' => 'System|custom|index|'))) && p('0,1') && e('custom,index');                     // 测试步骤4：自定义模块索引权限测试
r($adminTest->getHasPrivLinkTest(array('link' => ''))) && p() && e('0');                                                       // 测试步骤5：空链接字段的菜单
r($adminTest->getHasPrivLinkTest(array('link' => 'Invalid|invalid|method|', 'links' => array('Invalid|invalid|method|')))) && p() && e('0'); // 测试步骤6：所有链接都无权限的菜单
r($adminTest->getHasPrivLinkTest(array('link' => 'Invalid|invalid|method|', 'links' => array()))) && p() && e('0');           // 测试步骤7：备选链接为空数组的菜单