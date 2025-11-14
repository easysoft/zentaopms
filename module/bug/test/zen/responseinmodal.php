#!/usr/bin/env php
<?php

/**

title=测试 bugZen::responseInModal();
timeout=0
cid=15475

- 执行bugTest模块的responseInModalTest方法，参数是'', false, 'execution'
 - 属性result @success
 - 属性closeModal @1
 - 属性load @1
- 执行bugTest模块的responseInModalTest方法，参数是'保存成功', false, 'execution'
 - 属性result @success
 - 属性message @保存成功
- 执行bugTest模块的responseInModalTest方法，参数是'', true, 'execution'
 - 属性result @success
 - 属性closeModal @1
 - 属性callback @refreshKanban()
- 执行bugTest模块的responseInModalTest方法，参数是'更新成功', true, 'execution'
 - 属性result @success
 - 属性closeModal @1
 - 属性callback @refreshKanban()
- 执行bugTest模块的responseInModalTest方法，参数是'', false, 'product'
 - 属性result @success
 - 属性closeModal @1
 - 属性load @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$bugTest = new bugZenTest();

r($bugTest->responseInModalTest('', false, 'execution')) && p('result;closeModal;load') && e('success,1,1');
r($bugTest->responseInModalTest('保存成功', false, 'execution')) && p('result;message') && e('success,保存成功');
r($bugTest->responseInModalTest('', true, 'execution')) && p('result;closeModal;callback') && e('success,1,refreshKanban()');
r($bugTest->responseInModalTest('更新成功', true, 'execution')) && p('result;closeModal;callback') && e('success,1,refreshKanban()');
r($bugTest->responseInModalTest('', false, 'product')) && p('result;closeModal;load') && e('success,1,1');