#!/usr/bin/env php
<?php

/**

title=测试 apiZen::getMethod();
timeout=0
cid=0

- 执行apiTest模块的getMethodTest方法，参数是'/repo/zentaopms/module/api/model.php/create' 
 - 属性className @api
 - 属性methodName @create
- 执行apiTest模块的getMethodTest方法，参数是'/repo/zentaopms/module/api/model.php/publishLib', 'Model' 
 - 属性className @api
 - 属性methodName @publishLib
- 执行apiTest模块的getMethodTest方法，参数是'/repo/zentaopms/module/api/model.php/deleteRelease' 
 - 属性className @api
 - 属性methodName @deleteRelease
- 执行apiTest模块的getMethodTest方法，参数是'/repo/zentaopms/module/api/model.php/nonExistentMethod' 属性error @Method api::nonExistentMethod() does not exist
- 执行apiTest模块的getMethodTest方法，参数是'/repo/zentaopms/module/api/model.php/create' 
 - 属性startLine @695
 - 属性endLine @726
 - 属性post @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/api.unittest.class.php';

su('admin');

$apiTest = new apiTest();

r($apiTest->getMethodTest('/repo/zentaopms/module/api/model.php/create')) && p('className,methodName') && e('api,create');
r($apiTest->getMethodTest('/repo/zentaopms/module/api/model.php/publishLib', 'Model')) && p('className,methodName') && e('api,publishLib');
r($apiTest->getMethodTest('/repo/zentaopms/module/api/model.php/deleteRelease')) && p('className,methodName') && e('api,deleteRelease');
r($apiTest->getMethodTest('/repo/zentaopms/module/api/model.php/nonExistentMethod')) && p('error') && e('Method api::nonExistentMethod() does not exist');
r($apiTest->getMethodTest('/repo/zentaopms/module/api/model.php/create')) && p('startLine,endLine,post') && e('695,726,~~');