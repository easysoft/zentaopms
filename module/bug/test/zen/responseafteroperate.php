#!/usr/bin/env php
<?php

/**

title=测试 bugZen::responseAfterOperate();
timeout=0
cid=15474

- 执行bugTest模块的responseAfterOperateTest方法，参数是1, array
 - 属性result @success
 - 属性message @操作成功
- 执行bugTest模块的responseAfterOperateTest方法，参数是2, array
 - 属性result @success
 - 属性message @保存成功
- 执行bugTest模块的responseAfterOperateTest方法，参数是3, array
 - 属性result @success
 - 属性message @自定义消息
- 执行bugTest模块的responseAfterOperateTest方法，参数是4, array
 - 属性result @success
 - 属性message @看板操作
- 执行bugTest模块的responseAfterOperateTest方法，参数是5, array
 - 属性result @success
 - 属性message @状态更新

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 3) . '/control.php';
include dirname(__FILE__, 3) . '/zen.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$bugTest = new bugZenTest();

r($bugTest->responseAfterOperateTest(1, array(), '操作成功', false)) && p('result,message') && e('success,操作成功');
r($bugTest->responseAfterOperateTest(2, array(), '', false)) && p('result,message') && e('success,保存成功');
r($bugTest->responseAfterOperateTest(3, array(), '自定义消息', false)) && p('result,message') && e('success,自定义消息');
r($bugTest->responseAfterOperateTest(4, array(), '看板操作', true)) && p('result,message') && e('success,看板操作');
r($bugTest->responseAfterOperateTest(5, array(array('field' => 'status', 'old' => 'active', 'new' => 'resolved')), '状态更新', false)) && p('result,message') && e('success,状态更新');
