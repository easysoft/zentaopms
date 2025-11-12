#!/usr/bin/env php
<?php

/**

title=测试 docZen::formFromSession();
timeout=0
cid=0

- 测试session中存在完整数据
 - 属性0 @http://test.com
 - 属性1 @1,2,3
- 测试session中不存在数据
 - 属性0 @~~
 - 属性1 @~~
- 测试session中存在部分数据
 - 属性0 @http://project.com
 - 属性1 @10,20
- 测试session数据被删除
 - 属性0 @~~
 - 属性1 @~~
- 测试不同type获取不同session
 - 属性0 @http://execution.com
 - 属性1 @5,6,7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

// 测试步骤1:session中存在完整数据,验证url和idList
$_SESSION['zentaoListproduct'] = array('url' => 'http://test.com', 'idList' => '1,2,3', 'cols' => array('id', 'name'), 'data' => array('item1', 'item2'));
r($docTest->formFromSessionTest('product')) && p('0;1') && e('http://test.com,1,2,3');

// 测试步骤2:session中不存在数据,返回空值
r($docTest->formFromSessionTest('nonexistent')) && p('0;1') && e(',');

// 测试步骤3:session中存在部分数据(仅url和idList)
$_SESSION['zentaoListproject'] = array('url' => 'http://project.com', 'idList' => '10,20');
r($docTest->formFromSessionTest('project')) && p('0;1') && e('http://project.com,10,20');

// 测试步骤4:验证session数据被删除(再次调用同一type返回空)
r($docTest->formFromSessionTest('product')) && p('0;1') && e(',');

// 测试步骤5:不同type参数获取不同session
$_SESSION['zentaoListexecution'] = array('url' => 'http://execution.com', 'idList' => '5,6,7', 'cols' => array('status'), 'data' => array('test'));
r($docTest->formFromSessionTest('execution')) && p('0;1') && e('http://execution.com,5,6,7');
