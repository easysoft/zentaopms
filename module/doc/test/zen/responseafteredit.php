#!/usr/bin/env php
<?php

/**

title=测试 docModel->responseAfterEdit();
timeout=0
cid=1

- 获取修改后的返回信息属性result @success
- 获取修改后的返回信息属性message @保存成功
- 获取修改后的返回信息
 - 第doc条的id属性 @1
 - 第doc条的title属性 @文档标题1
- 获取修改后的返回信息属性result @success
- 获取修改后的返回信息属性message @保存成功
- 获取修改后的返回信息
 - 第doc条的id属性 @2
 - 第doc条的title属性 @文档标题2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('doclib')->gen(50);
zenData('doc')->gen(50);
zenData('doccontent')->gen(50);
su('admin');

global $tester;
$tester->app->setModuleName('doc');
$docZen = initReference('doc');
$method = $docZen->getMethod('responseAfterEdit');
$method->setAccessible(true);
$result = $method->invokeArgs($docZen->newInstance(), [$tester->loadModel('doc')->fetchByID(1)]);

r($result) && p('result')  && e('success');             // 获取修改后的返回信息
r($result) && p('message') && e('保存成功');            // 获取修改后的返回信息
r($result) && p('doc:id,title')   && e('1,文档标题1');  // 获取修改后的返回信息

$result = $method->invokeArgs($docZen->newInstance(), [$tester->loadModel('doc')->fetchByID(2)]);

r($result) && p('result')  && e('success');             // 获取修改后的返回信息
r($result) && p('message') && e('保存成功');            // 获取修改后的返回信息
r($result) && p('doc:id,title')   && e('2,文档标题2');  // 获取修改后的返回信息
