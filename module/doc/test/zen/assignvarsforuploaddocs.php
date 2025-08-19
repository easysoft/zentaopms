#!/usr/bin/env php
<?php

/**

title=测试 docModel->assignVarsForUploadDocs();
timeout=0
cid=1

- 获取上传文档的返回信息属性title @产品主库-导入
- 获取上传文档的返回信息属性linkType @product
- 获取上传文档的返回信息属性libName @产品主库
- 获取上传文档的返回信息属性docID @1
- 获取上传文档的返回信息
 - 第doc条的id属性 @1
 - 第doc条的title属性 @文档标题1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('doclib')->gen(50);
zenData('doc')->gen(50);
zenData('doccontent')->gen(50);
su('admin');

global $tester;
$tester->app->setModuleName('doc');
$docZen = initReference('doc');
$method = $docZen->getMethod('assignVarsForUploadDocs');
$method->setAccessible(true);
$result = $method->invokeArgs($docZen->newInstance(), [1,'product',1,1]);

r($result) && p('title')        && e('产品主库-导入'); // 获取上传文档的返回信息
r($result) && p('linkType')     && e('product');       // 获取上传文档的返回信息
r($result) && p('libName')      && e('产品主库');      // 获取上传文档的返回信息
r($result) && p('docID')        && e('1');             // 获取上传文档的返回信息
r($result) && p('doc:id,title') && e('1,文档标题1');   // 获取上传文档的返回信息
