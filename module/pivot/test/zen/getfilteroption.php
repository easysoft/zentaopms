#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::getFilterOptionUrl();
timeout=0
cid=17458

- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter1 属性url @getfilteroption.php?m=pivot&f=ajaxGetSysOptions&search={search}
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter2 属性method @post
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter3, '', $fieldSettings3 第data条的type属性 @options
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter4, '', $fieldSettings4 第data条的values属性 @high
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter5, '', $fieldSettings5 第data条的saveAs属性 @bugType
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter6, '', $fieldSettings6 第data条的field属性 @account
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是$filter7, '', $fieldSettings7 属性method @post

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

zenData('user')->gen(1);

su('admin');

$pivotTest = new pivotZenTest();

// 测试步骤1: 筛选器 from=query 的情况,验证 URL
$filter1 = array('field' => 'status', 'from' => 'query', 'typeOption' => 'bug', 'default' => 'active');
r($pivotTest->getFilterOptionUrlTest($filter1)) && p('url') && e('getfilteroption.php?m=pivot&f=ajaxGetSysOptions&search={search}');

// 测试步骤2: 筛选器 from=query 的情况,验证 method
$filter2 = array('field' => 'status', 'from' => 'query', 'typeOption' => 'bug', 'default' => 'active');
r($pivotTest->getFilterOptionUrlTest($filter2)) && p('method') && e('post');

// 测试步骤3: 筛选器 from=result 且字段类型为 options
$filter3 = array('field' => 'priority', 'from' => 'result', 'default' => '1');
$fieldSettings3 = array('priority' => array('type' => 'options', 'object' => 'bug', 'field' => 'priority'));
r($pivotTest->getFilterOptionUrlTest($filter3, '', $fieldSettings3)) && p('data:type') && e('options');

// 测试步骤4: 测试默认值为简单字符串
$filter4 = array('field' => 'severity', 'from' => 'result', 'default' => 'high');
$fieldSettings4 = array('severity' => array('type' => 'options', 'object' => 'bug', 'field' => 'severity'));
r($pivotTest->getFilterOptionUrlTest($filter4, '', $fieldSettings4)) && p('data:values') && e('high');

// 测试步骤5: 测试带有 saveAs 参数
$filter5 = array('field' => 'type', 'from' => 'result', 'default' => 'codeerror', 'saveAs' => 'bugType');
$fieldSettings5 = array('type' => array('type' => 'options', 'object' => 'bug', 'field' => 'type'));
r($pivotTest->getFilterOptionUrlTest($filter5, '', $fieldSettings5)) && p('data:saveAs') && e('bugType');

// 测试步骤6: 测试字段类型为 object
$filter6 = array('field' => 'openedBy', 'from' => 'result', 'default' => 'admin');
$fieldSettings6 = array('openedBy' => array('type' => 'object', 'object' => 'user', 'field' => 'account'));
r($pivotTest->getFilterOptionUrlTest($filter6, '', $fieldSettings6)) && p('data:field') && e('account');

// 测试步骤7: 测试默认值为数组,验证method
$filter7 = array('field' => 'status', 'from' => 'result', 'default' => array('active', 'resolved', 'closed'));
$fieldSettings7 = array('status' => array('type' => 'options', 'object' => 'bug', 'field' => 'status'));
r($pivotTest->getFilterOptionUrlTest($filter7, '', $fieldSettings7)) && p('method') && e('post');