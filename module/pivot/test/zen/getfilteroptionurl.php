#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::getFilterOptionUrl();
timeout=0
cid=17459

- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter1
 - 属性method @post
 - 第data条的type属性 @bug_status
 - 第data条的values属性 @active
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter2, $sql2, $fieldSettings2
 - 属性method @post
 - 第data条的type属性 @user
 - 第data条的object属性 @user
 - 第data条的field属性 @assignedTo
 - 第data条的values属性 @admin
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter3, $sql3, $fieldSettings3
 - 属性method @post
 - 第data条的type属性 @options
 - 第data条的field属性 @priority
 - 第data条的values属性 @1
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter4, $sql4, $fieldSettings4
 - 属性method @post
 - 第data条的type属性 @object
 - 第data条的object属性 @product
 - 第data条的field属性 @id
 - 第data条的saveAs属性 @productID
 - 第data条的values属性 @1
- 执行$result5->data['values'] @wait,doing,done

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

su('admin');

$pivotTest = new pivotZenTest();

// 测试步骤1:from为query类型的筛选器 - 期望返回包含typeOption的数据结构
$filter1 = array('field' => 'status', 'from' => 'query', 'typeOption' => 'bug_status', 'default' => 'active');
r($pivotTest->getFilterOptionUrlTest($filter1)) && p('method;data:type,values') && e('post;bug_status,active');

// 测试步骤2:from为result类型且字段类型为user的筛选器 - 期望返回包含字段详细配置的数据结构
$filter2 = array('field' => 'assignedTo', 'from' => 'result', 'default' => 'admin');
$fieldSettings2 = array('assignedTo' => (object)array('type' => 'user', 'object' => 'user', 'field' => 'account'));
$sql2 = 'SELECT * FROM zt_task';
r($pivotTest->getFilterOptionUrlTest($filter2, $sql2, $fieldSettings2)) && p('method;data:type,object,field,values') && e('post;user,user,assignedTo,admin');

// 测试步骤3:字段类型为options的筛选器 - 期望正确处理options类型的字段配置
$filter3 = array('field' => 'pri', 'from' => 'result', 'default' => '1');
$fieldSettings3 = array('pri' => (object)array('type' => 'options', 'object' => '', 'field' => 'priority'));
$sql3 = 'SELECT * FROM zt_bug';
r($pivotTest->getFilterOptionUrlTest($filter3, $sql3, $fieldSettings3)) && p('method;data:type,field,values') && e('post;options,priority,1');

// 测试步骤4:字段类型为object的筛选器 - 期望正确处理object类型的字段配置
$filter4 = array('field' => 'product', 'from' => 'result', 'default' => '1', 'saveAs' => 'productID');
$fieldSettings4 = array('product' => (object)array('type' => 'object', 'object' => 'product', 'field' => 'id'));
$sql4 = 'SELECT * FROM zt_product';
r($pivotTest->getFilterOptionUrlTest($filter4, $sql4, $fieldSettings4)) && p('method;data:type,object,field,saveAs,values') && e('post;object,product,id,productID,1');

// 测试步骤5:包含default值数组的筛选器 - 期望将数组值正确转换为逗号分隔字符串
$filter5 = array('field' => 'status', 'from' => 'query', 'typeOption' => 'task_status', 'default' => array('wait', 'doing', 'done'));
$result5 = $pivotTest->getFilterOptionUrlTest($filter5);
r($result5->data['values']) && p() && e('wait,doing,done');