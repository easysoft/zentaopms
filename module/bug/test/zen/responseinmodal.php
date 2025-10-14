#!/usr/bin/env php
<?php

/**

title=测试 bugZen::responseInModal();
timeout=0
cid=0

- 执行bugTest模块的responseInModalTest方法 
 - 属性result @success
 - 属性closeModal @1
 - 属性load @1
- 执行bugTest模块的responseInModalTest方法，参数是'自定义消息' 
 - 属性result @success
 - 属性message @自定义消息
 - 属性closeModal @1
- 执行bugTest模块的responseInModalTest方法，参数是'', true, 'execution' 
 - 属性result @success
 - 属性closeModal @1
 - 属性callback @refreshKanban()
- 执行bugTest模块的responseInModalTest方法，参数是'测试消息', false, 'qa' 
 - 属性result @success
 - 属性message @测试消息
 - 属性load @1
- 执行bugTest模块的responseInModalTest方法，参数是'', false, 'execution' 
 - 属性result @success
 - 属性closeModal @1
 - 属性load @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

su('admin');

$bugTest = new bugTest();

r($bugTest->responseInModalTest()) && p('result,closeModal,load') && e('success,1,1');
r($bugTest->responseInModalTest('自定义消息')) && p('result,message,closeModal') && e('success,自定义消息,1');
r($bugTest->responseInModalTest('', true, 'execution')) && p('result,closeModal,callback') && e('success,1,refreshKanban()');
r($bugTest->responseInModalTest('测试消息', false, 'qa')) && p('result,message,load') && e('success,测试消息,1');
r($bugTest->responseInModalTest('', false, 'execution')) && p('result,closeModal,load') && e('success,1,1');